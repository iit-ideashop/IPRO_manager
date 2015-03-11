<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class housekeeping extends Command {

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
	public function fire()
	{
		//The booleans this app looks for
		$checks['open_orders'] = false;
		$checks['open_budget_request'] = false;
		$checks['LabTagItems'] = false;
		$checks['sendEmail'] = false;
		//Let's pull the orders from the DB and make sure that there are no open orders
		//Start by pulling semester
		$semester = Semester::where('active','=',true)->first();//Pull the last active semester
		//Pull the projects so we are only looking at orders for those projects
		$project_ids = $semester->Projects()->lists('id');
		//$projects is an array of projects with ID in the current semester
		//Take this and now filter orders to find open orders for this semester
		$orders = Order::whereIn('ClassID', $project_ids)->where('status','=','1')->get();
		if(!$orders->isEmpty()){
			$checks['open_orders'] = true;
		}

		//Next we look for any budget requests made by projects in the active semester
		$account_ids = Account::whereIn('ClassID',$project_ids)->lists('id');
		$budgetRequests = BudgetRequest::whereIn('AccountID',$account_ids)->where('Status','=','1')->get();
		if(!$budgetRequests->isEmpty()){
			$checks['open_budget_request'] = true;
		}

		//Check for items that belong to Proto lab tag
		$order_ids = Order::whereIn('ClassID',$project_ids)->lists('id');
		$items = Item::whereIn('OrderID',$order_ids)->where('status','=','7')->get();
		if(!$items->isEmpty()){
			$checks['LabTagItems'] = true;
			//We need to notify the protolab_requires_order distribution list of an open order
			//Grab people we need to notify
			$protolab_users = DistributionList::where("distributionList","=","protolab_requires_order")->lists("UserID");
			$protolab_users_obj = User::whereIn("id",$protolab_users)->get(); //User objects of anyone on the protolab distribution list
			Mail::send('emails.prototypingLab.protolab_requires_order', array('items' => $items), function ($message) use ($protolab_users_obj) {
				foreach ($protolab_users_obj as $protolab_user) {
					$message->to($protolab_user->Email, $protolab_user->FirstName . ' ' . $protolab_user->LastName);
				}
				$message->subject('[IPRO Manager] Prototyping Lab Action Required');
			});
		}


		//Send that admin email thing if there are any tasks needing work
		/*foreach($checks as $key=>$val){
			if($val){
				$checks['sendEmail'] = true;
			}
		}
		if($checks['sendEmail']) {
			$admins = User::Where("isAdmin", "=", true)->get();
			Mail::send('emails.housekeeping', array('checks' => $checks), function ($message) use ($admins) {
				foreach ($admins as $admin) {
					$message->to($admin->Email, $admin->FirstName . ' ' . $admin->LastName);
				}
				$message->subject('IPRO Manager action required!');
			});
			$this->info('sending admin email');
		}*/
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
