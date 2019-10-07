<?php

use Illuminate\Database\Seeder;

class AdditionalFunctionalRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array( 

        	array('id' =>736, 'name' => 'Any'),
        	array('id' =>737, 'name' => 'Accounting / Tax / Company Secretary / Audit'),
			array('id' =>738, 'name' => 'Architects / Interior Design / Naval Arch.'),
			array('id' =>739, 'name' => 'Art Director / Graphic / Web Designer'),
			array('id' =>740, 'name' => 'Hotels / Restaurant Management'),
			array('id' =>741, 'name' => 'Content / Editors / Journalists'),
			array('id' =>742, 'name' => 'Banking / Insurance'),
			array('id' =>743, 'name' => 'Corporate Planning / Consulting / Strategy'),
			array('id' =>744, 'name' => 'ITES / BPO / Operations / Customer Service / Telecalling'),
			array('id' =>745, 'name' => 'Entrepreneur / Businessman / Outside Management Consultant'),
			array('id' =>746, 'name' => 'Export / Import'),
			array('id' =>747, 'name' => 'Front Office Staff / Secretarial / Computer Operator'),
			array('id' =>748, 'name' => 'HR / Admin / PM / IR / Training'),
			array('id' =>749, 'name' => 'Legal / Law'),
			array('id' =>750, 'name' => 'Purchase / SCM'),
			array('id' =>751, 'name' => 'Mktg / Advtg / MR / Media Planning / PR / Corp. Comm.'),
			array('id' =>752, 'name' => 'Medical Professional / Healthcare Practitioner / Technician'),
			array('id' =>753, 'name' => 'Packaging Development'),
			array('id' =>754, 'name' => 'Production / Service Engineering / Manufacturing / Maintenance'),
			array('id' =>755, 'name' => 'Project Management / Site Engineers'),
			array('id' =>756, 'name' => 'R&amp;D / Engineering Design'),
			array('id' =>757, 'name' => 'Sales / Business Development / Client Servicing'),
			array('id' =>758, 'name' => 'Software Development - Application Programming'),
			array('id' =>759, 'name' => 'Software Development - Client Server'),
			array('id' =>760, 'name' => 'Software Development - Database Administration'),
			array('id' =>761, 'name' => 'Software Development - ERP / CRM'),
			array('id' =>762, 'name' => 'Software Development - Embedded Technologies'),
			array('id' =>763, 'name' => 'Software Development - Network Administration'),
			array('id' =>764, 'name' => 'Software Development - Others'),
			array('id' =>765, 'name' => 'Software Development - QA and Testing'),
			array('id' =>766, 'name' => 'Software Development - System Programming'),
			array('id' =>767, 'name' => 'Software Development - Telecom Software'),
			array('id' =>768, 'name' => 'Software Development - e-commerce / Internet Technologies'),
			array('id' =>769, 'name' => 'Software Development - Systems / EDP / MIS'),
			array('id' =>770, 'name' => 'Teaching / Education / Language Specialist'),
			array('id' =>771, 'name' => 'Telecom / IT-Hardware / Tech. Staff / Support'),
			array('id' =>772, 'name' => 'Top Management'),
			array('id' =>773, 'name' => 'Any Other'),
			array('id' =>774, 'name' => 'Fashion'),
			array('id' =>775, 'name' => 'Anchoring / TV / Films / Production'),
			array('id' =>776, 'name' => 'Airline / Reservations / Ticketing / Travel'),
			array('id' =>777, 'name' => 'Security'),
			array('id' =>778, 'name' => 'Agent'),
			array('id' =>779, 'name' => 'Analytics & Business Intelligence'),
			array('id' =>780, 'name' => 'Shipping'),
			array('id' =>781, 'name' => 'Software Development - ALL'),
		);

        DB::table("functional_roles")->insert($data);

    }
}
