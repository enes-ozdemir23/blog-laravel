<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; //str_slug kullanımı kalktıüı için aşağıdaki gibi kullandım ve bu satırı ekledim


class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages=['Hakkımızda','Kariyer','Vizyonumuz','Misyonumuz'];
        $count=0;
        foreach ($pages as $page){
            $count++;
            DB::table('pages')->insert([
                'title'=>$page,
                'slug'=>Str::slug($page),
                'image'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGWuCKeC2vkY2RWGnIPtnX3W3iBuswH1578yiPry-o4vyo9Z04bmWEYsyPOwVrRPIkw04&usqp=CAU',
                'content'=>'Seeder, veritabanına varsayılan verileri eklemek için kullanılır.
                            Seeder kullanarak, web uygulamanızı başlatırken veritabanına varsayılan verileri ekleyebilirsiniz.
                            Bu işlem, test verileri oluşturmak ve örnek verileri veritabanına eklemek için kullanışlıdır.',
                'order'=>$count,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }
    }
}
