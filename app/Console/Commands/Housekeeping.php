<?php
namespace App\Console\Commands;

use Semester, Order, Item, DistributionList, User;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Mail;

class Housekeeping extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'housekeeping';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Runs IPRO Manager housekeeping script which emails admins on things happening in the app';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        //This script will fire every hour on the hour and we have to control what gets emailed out.
        //Get the current time
        $hour = date("G",time());
        //Based on the hour we will run certain code
        $this->info('the current hour is: '.$hour);
        //Start by pulling semester
		$semester = Semester::where('active','=',true)->first();//Pull the last active semester
		//Pull the projects so we are only looking at orders for those projects
		$project_ids = $semester->Projects()->pluck('id');
		//$projects is an array of projects with ID in the current semester

        //This will only run at hour 8,16
        if (($hour == 8)||($hour == 16)){
            $this->info('Processing Prototyping lab orders');
            //Check for items that belong to Proto lab tag
            $order_ids = Order::whereIn('ClassID', $project_ids)->pluck('id');
            $items = Item::whereIn('OrderID', $order_ids)->where('status', '=', '7')->get();
            if (!$items->isEmpty()) {
                //We need to notify the protolab_requires_order distribution list of an open order
                //Grab people we need to notify
                $protolab_users = DistributionList::where("distributionList", "=", "protolab_requires_order")->pluck("UserID");
                $protolab_users_obj = User::whereIn("id", $protolab_users)->get(); //User objects of anyone on the protolab distribution list
                Mail::send('emails.prototypingLab.protolab_requires_order', array('items' => $items), function ($message) use ($protolab_users_obj) {
                    foreach ($protolab_users_obj as $protolab_user) {
                        $message->to($protolab_user->Email, $protolab_user->FirstName . ' ' . $protolab_user->LastName);
                    }
                    $message->subject('[IPRO Manager] Prototyping Lab Action Required');
                });
            }
        }

        if(($hour == 9)||($hour == 13)||($hour == 16)){
            $this->info('Processing Print shop queue');
            //Send the email to print admins that there are prints in the queue awaiting print and are from this semester
            $printSubmissions = PrintSubmission::where("status","=",3)->whereIn("ProjectID",$project_ids)->get();
            if(!$printSubmissions->isEmpty()){
                //We have print submissions waiting for print
                $printAdmins = DistributionList::where("distributionList", "=", "printing_awaitingPrintQueue")->pluck("UserID");
                $printAdminsObj =  User::whereIn("id", $printAdmins)->get();
                Mail::send('emails.printing.printingAwaitingPrintQueue', array('files' => $printSubmissions), function ($message) use ($printAdminsObj) {
                    foreach ($printAdminsObj as $printAdminObj) {
                        $message->to($printAdminObj->Email, $printAdminObj->FirstName . ' ' . $printAdminObj->LastName);
                    }
                    $message->subject('[IPRO Manager] Files awaiting print!');
                });
            }
        }

		$this->info('successfully completed housekeeping script');
		return true;
	}
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
