<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupDompdfFonts extends Command
{
    /**
     * The name and signature of the console command.
    *
    * @var string
    */
    // protected $signature = 'app:setup-dompdf-fonts';
    protected $signature = 'dompdf:setup-fonts';

    /**
     * The console command description.
    *
    * @var string
    */
    // protected $description = 'Command description';
    protected $description = 'Cria a pasta storage/fonts e registra fontes para uso com DomPDF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $fontDir = storage_path('fonts');

        // 1. Criar pasta fonts se não existir
        if (!File::exists($fontDir)) {
            File::makeDirectory($fontDir, 0755, true);
            $this->info("📁 Pasta criada: storage/fonts");
        } else {
            $this->info("✅ Pasta já existe: storage/fonts");
        }

        // 2. Copiar fonte Roboto se quiser
        $sourceFont = resource_path('fonts/Roboto-Regular.ttf');
        $targetFont = $fontDir . '/Roboto-Regular.ttf';

        if (File::exists($sourceFont)) {
            File::copy($sourceFont, $targetFont);
            $this->info("📄 Fonte copiada: Roboto-Regular.ttf");
        } else {
            $this->warn("⚠️ Fonte Roboto não encontrada em resources/fonts. Copie manualmente se necessário.");
        }

        // 3. Informar que está pronto para uso
        $this->info("🎉 Fontes prontas para uso com DomPDF. Configure 'font_dir' e 'font_cache' em config/dompdf.php.");
    }

}