<?php

use App\Models\Pages\Package;
use Illuminate\Database\Seeder;

class PackagesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //public/site/img/settings/packages
        $packages = [
            ['image' => 'site/img/settings/packages/package1.png','bet'=>'10','price'=>'100'],
            ['image' => 'site/img/settings/packages/package2.png','bet'=>'50','bonus'=>'5','price'=>'500'],
            ['image' => 'site/img/settings/packages/package3.png','bet'=>'100','bonus'=>'20','price'=>'1000'],
            ['image' => 'site/img/settings/packages/package4.png','bet'=>'500','bonus'=>'150','price'=>'5000'],
            ['image' => 'site/img/settings/packages/package5.png','bet'=>'1000','bonus'=>'500','price'=>'10000'],
        ];
        foreach ($packages as $package)
            Package::create($package);
    }
}
