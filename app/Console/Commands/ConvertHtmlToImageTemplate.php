<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertHtmlToImageTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   protected $signature = 'convert:html-to-image-template {html : public/index.html} {output : public/image.png}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert HTML to image using wkhtmltoimage';

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
        $html = $this->argument('html');
        $output = $this->argument('output');

      exec("wkhtmltoimage --format png --width 1440 --height 1040 --quality 75 $html $output");
      $this->info("HTML converted to image and saved to $output");
    }
}
