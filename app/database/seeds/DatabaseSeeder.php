<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $this->call('BudgetStatusTableSeeder');
        $this->call('OrderStatusTableSeeder');
        $this->call('ItemStatusTableSeeder');
        $this->call('classAccessTypesTableSeeder');
        /*
        $this->call('UserTableSeeder');
        $this->call('SemesterTableSeeder');
        $this->call('ProjectTableSeeder');
        $this->call('AccountTableSeeder');
        $this->call('ProjectPeopleTableSeeder');
        $this->call('OrderTableSeeder');
        $this->call('ItemTableSeeder');
        $this->call('OrderNotesTableSeeder');
        $this->call('BudgetRequestTableSeeder');
        $this->call('BudgetTableSeeder');
        $this->call('ledgerTableSeeder');
        $this->command->info('Database Seeded Successfully');
         */
    }
}
class BudgetStatusTableSeeder extends Seeder {
    public function run()
    {
        DB::table('budgetStatus')->delete();
        DB::table('budgetStatus')->insert(array(array('id'=>1,'Status'=>'Requested'),array('id'=>2,'Status'=>'Approved'),array('id'=>3,'Status'=>'Rejected')));
    }
}
class OrderStatusTableSeeder extends Seeder {
    public function run()
    {
        DB::table('orderStatus')->delete();
        DB::table('orderStatus')->insert(array(array('id'=>1,'Status'=>'Requested'),array('id'=>2,'Status'=>'Ordered'),array('id'=>3,'Status'=>'Ready for Pickup'),array('id'=>4,'Status'=>'Completed'),array('id'=>5,'Status'=>'On-Hold')));
    }
}
class ItemStatusTableSeeder extends Seeder {
    public function run()
    {
        DB::table('itemStatus')->delete();
        DB::table('itemStatus')->insert(array(array('id'=>1,'Status'=>'Requested'),array('id'=>2,'Status'=>'Approved for Purchase'),array('id'=>3,'Status'=>'Ordered'),array('id'=>4,'Status'=>'Received'),array('id'=>5,'Status'=>'Picked Up'),array('id'=>6,'Status'=>'Cancelled'),array('id'=>7,'Status'=>'Checking IdeaShop Stock'),array('id'=>8,'Status'=>'On-Hold')));
    }
}
class classAccessTypesTableSeeder extends Seeder {
    public function run()
    {
        DB::table('peopleClassesAccessType')->delete();
        DB::table('peopleClassesAccessType')->insert(array(array('id'=>'1','AccessType'=>'User'),array('id'=>2,'AccessType'=>'TA'),array('id'=>3,'AccessType'=>'Instructor')));
    }
}
class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();
        User::create(array('FirstName' => 'Bartlomiej', 'LastName'=>'Dworak', 'email' => 'bdworak@hawk.iit.edu','modifiedBy'=>'SYSTEM','isAdmin'=>true));
    }
}
class SemesterTableSeeder extends Seeder {
    public function run()
    {
        DB::table('semesters')->delete();
        Semester::create(array('Name'=>'Fall 2014','Active'=>1,'ActiveStart'=>'2014-08-04 00:00:00','ActiveEnd'=>'2014-12-12 00:00:00','ModifiedBy'=>1));
    }
}
class ProjectTableSeeder extends Seeder {
    public function run()
    {
        DB::table('projects')->delete();
        Project::create(array('UID'=>'IPRO 301', 'Name'=>'IPRO Management Console','Description'=>'A cool project','Semester'=>1,'modifiedBy'=>1));
    }
}
class AccountTableSeeder extends Seeder {
    public function run()
    {
        DB::table('accounts')->delete();
        Account::create(array('ClassID'=>1,'Balance'=>500));
    }
}
class ProjectPeopleTableSeeder extends Seeder {
    public function run()
    {
        DB::table('peopleProjects')->delete();
        PeopleProject::create(array('UserID'=>1,'ClassID'=>1,'AccessType'=>1,'ModifiedBy'=>1));
    }
}
class OrderTableSeeder extends Seeder {
    public function run()
    {
        DB::table('orders')->delete();
        Order::create(array('PeopleID'=>1,'ClassID'=>1,'OrderTotal'=>160.00,'Description'=>'Nuts and Bolts','Status'=>1,'ModifiedBy'=>1));
        Order::create(array('PeopleID'=>1,'ClassID'=>1,'OrderTotal'=>55.00,'Description'=>'Electronic Components','Status'=>2,'ModifiedBy'=>1));
    }
}
class ItemTableSeeder extends Seeder {
    public function run()
    {
        DB::table('items')->delete();
        Item::create(array('OrderID'=>1,'Name'=>'Nuts','Link'=>'http://amazon.com/nuts','PartNumber'=>'222','Cost'=>20.00,'Quantity'=>2,'TotalCost'=>40,'Justification'=>'We need nuts','Status'=>1,'ModifiedBy'=>1));
        Item::create(array('OrderID'=>1,'Name'=>'Bolts','Link'=>'http://amazon.com/Bolts','PartNumber'=>'223','Cost'=>60.00,'Quantity'=>2,'TotalCost'=>120,'Justification'=>'We need nuts','Status'=>1,'ModifiedBy'=>1));
        Item::create(array('OrderID'=>2,'Name'=>'Arduino','Link'=>'http://amazon.com/electronics','PartNumber'=>'1','Cost'=>43.99,'Quantity'=>1,'TotalCost'=>43.99,'Justification'=>'We need arduino','Status'=>2,'ModifiedBy'=>1));
        Item::create(array('OrderID'=>2,'Name'=>'Soldering Iron','Link'=>'http://amazon.com/electronics','PartNumber'=>'2','Cost'=>11.01,'Quantity'=>1,'TotalCost'=>11.01,'Justification'=>'We need soldering iron','Status'=>2,'ModifiedBy'=>1));
    }
}
class OrderNotesTableSeeder extends Seeder {
    public function run()
    {
        DB::table('orderNotes')->delete();
        OrderNote::create(array('OrderID'=>1,'ItemID'=>1,'Notes'=>"Got the Nuts",'EnteredBy'=>1));
        OrderNote::create(array('OrderID'=>1,'ItemID'=>2,'Notes'=>"Got the Bolts",'EnteredBy'=>1));
        OrderNote::create(array('OrderID'=>2,'ItemID'=>3,'Notes'=>"Got the Arduino",'EnteredBy'=>1));
        OrderNote::create(array('OrderID'=>2,'ItemID'=>4,'Notes'=>"Got the soldering iron",'EnteredBy'=>1));
    }
}
class BudgetRequestTableSeeder extends Seeder {
    public function run()
    {
        DB::table('budgetRequests')->delete();
        BudgetRequest::create(array('AccountID'=>1,'Amount'=>500.00,'Request'=>'Initial funding request','Status'=>1,'Requester'=>1,'ModifiedBy'=>1));
        BudgetRequest::create(array('AccountID'=>1,'Amount'=>500.00,'Request'=>'More funding request','Status'=>1,'Requester'=>1,'ModifiedBy'=>1));        
    }
}
class BudgetTableSeeder extends Seeder {
    public function run()
    {
        DB::table('budgets')->delete();
        Budget::create(array('AccountID'=>1,'Amount'=>500.00,'Terms'=>'Initial funding request','Requester'=>1,'Approver'=>1));
        Budget::create(array('AccountID'=>1,'Amount'=>500.00,'Terms'=>'More funding request','Requester'=>1,'Approver'=>1));        
    }
}
class ledgerTableSeeder extends Seeder {
    public function run()
    {
        DB::table('ledgerEntries')->delete();
        ledgerEntry::create(array('AccountNumber'=>1,'EntryType'=>'ORDER','EntryTypeID'=>1,'Credit'=>500,'Debit'=>0,'NewAccountBalance'=>500.00,'EnteredBy'=>1));
        ledgerEntry::create(array('AccountNumber'=>1,'EntryType'=>'REIMBURSEMENT','EntryTypeID'=>0,'Credit'=>500,'Debit'=>0,'NewAccountBalance'=>500.00,'EnteredBy'=>1));
        ledgerEntry::create(array('AccountNumber'=>1,'EntryType'=>'REIMBURSEMENT','EntryTypeID'=>0,'Credit'=>500,'Debit'=>0,'NewAccountBalance'=>500.00,'EnteredBy'=>1));
        ledgerEntry::create(array('AccountNumber'=>1,'EntryType'=>'BUDGET','EntryTypeID'=>0,'Credit'=>0,'Debit'=>0,'NewAccountBalance'=>500.00,'EnteredBy'=>1));
        ledgerEntry::create(array('AccountNumber'=>1,'EntryType'=>'RECONCILE','EntryTypeID'=>2,'Credit'=>0,'Debit'=>500,'NewAccountBalance'=>500.00,'EnteredBy'=>1));
    }
}

/*
 * unseeding code
 * 
DROP TABLE orderNotes;
DROP TABLE ledgerEntries;
DROP TABLE budgets;
DROP TABLE budgetRequests;
DROP TABLE items;
DROP TABLE orders;
DROP TABLE PeopleProjects;
DROP TABLE accounts;
DROP TABLE projects;
DROP TABLE semesters;
DROP TABLE users;
DROP TABLE peopleClassesAccessType;
DROP TABLE itemStatus;
DROP TABLE orderStatus;
DROP TABLE budgetStatus;
DROP TABLE migrations;
 */