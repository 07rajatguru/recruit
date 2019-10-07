<?php

use Illuminate\Database\Seeder;

class EductionaListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("eduction_qualification")->truncate();

        $data = array(

        	array('id' =>1, 'name' => 'Aviation'),
			array('id' =>2, 'name' => 'Bachelor of Arts (B.A)'),
			array('id' =>3, 'name' => 'Bachelor of Architecture (B.Arch)'),
			array('id' =>4, 'name' => 'Bachelor of Business Administration (B.B.A)'),
			array('id' =>5, 'name' => 'Bachelor Of Computer Application (B.C.A)'),
			array('id' =>6, 'name' => 'Bachelor of Commerce (B.Com)'),
			array('id' =>7, 'name' => 'Bachelor of Dental Surgery (B.D.S)'),
			array('id' =>8, 'name' => 'Bachelor Of Technology (B.Tech/B.E)'),
			array('id' =>9, 'name' => 'Bachelor of Education (B.Ed)'),
			array('id' =>10, 'name' => 'Bachelor of Ayurvedic Medicine and Surgery (BAMS)Â '),
			array('id' =>11, 'name' => 'Bachelor in Hotel Management (B.H.M)'),
			array('id' =>12, 'name' => 'Bachelor of Homeopathic Medicine and Surgery (BHMS)'),
			array('id' =>13, 'name' => 'Bachelors of Law (B.L/L.L.B)'),
			array('id' =>14, 'name' => 'Bachelor Of Pharmacy (B.Pharm)'),
			array('id' =>15, 'name' => 'Bachelor of Science (B.Sc)'),
			array('id' =>16, 'name' => 'Bachelor of Social Work (B.S.W)'),
			array('id' =>17, 'name' => 'Chartered Accountancy (C.A)'),
			array('id' =>18, 'name' => 'Chartered Accountancy Inter (C.A Inter)'),
			array('id' =>19, 'name' => '12th Class (XII)'),
			array('id' =>20, 'name' => 'Company Secretary'),
			array('id' =>21, 'name' => 'Doctor of Social Work (D.S.W)'),
			array('id' =>22, 'name' => 'Diploma'),
			array('id' =>23, 'name' => 'ICWA'),
			array('id' =>24, 'name' => 'ICWA Inter'),
			array('id' =>25, 'name' => 'Masters in Arts (M.A)'),
			array('id' =>26, 'name' => 'Masters of Architecture (M.Arch)'),
			array('id' =>27, 'name' => 'Master OF Business Administration (M.B.A)'),
			array('id' =>28, 'name' => 'MBBS'),
			array('id' =>29, 'name' => 'Master in Computer Application (M.C.A)'),
			array('id' =>30, 'name' => 'Master of Commerce (M.Com)'),
			array('id' =>31, 'name' => 'Doctor of Medicine (M.D/M.S)'),
			array('id' =>32, 'name' => 'Master of Education (M.Ed)'),
			array('id' =>33, 'name' => 'Masters in Technology (M.Tech/M.E/M.Sc)'),
			array('id' =>34, 'name' => 'Master of Law (M.L/L.L.M)'),
			array('id' =>35, 'name' => 'Master of Pharmacy (M.Pharm)'),
			array('id' =>36, 'name' => 'Master of Philosophy (M.Phil)'),
			array('id' =>37, 'name' => 'Master of Social Work (M.S.W)'),
			array('id' =>38, 'name' => 'Post Graduate Diploma in Computer ApplicationsÂ (PGDCA)'),
			array('id' =>39, 'name' => 'PGDM'),
			array('id' =>40, 'name' => 'Post Graduate Programme in Management for Executives (PGPX)'),
			array('id' =>41, 'name' => 'Phd'),
			array('id' =>42, 'name' => 'Other'),

		);

        DB::table("eduction_qualification")->insert($data);

    }
}
