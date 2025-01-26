<?php


namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'width' => 'required|integer',
            'height' => 'required|integer',
            'color' => 'required|string',
            'link' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $module = Module::create($request->all());

        return response()->json(['id' => $module->id], 201);
    }

    public function download($id)
    {
        $module = Module::findOrFail($id);

        $tempDir = sys_get_temp_dir() . '/module_' . $id;
        if (!file_exists($tempDir)) {
            mkdir($tempDir);
        }

        $this->generateHTML($module, $tempDir);
        $this->generateCSS($module, $tempDir);
        $this->generateJS($module, $tempDir);

        $zipPath = $tempDir . '/module.zip';
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach (['index.html', 'styles.css', 'script.js'] as $file) {
            $zip->addFile($tempDir . '/' . $file, $file);
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function generateHTML($module, $dir)
    {
        $html = <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <link rel="stylesheet" href="styles.css">
            </head>
            <body>
                <div class="module"></div>
                <script src="script.js"></script>
            </body>
            </html>
            HTML;

        file_put_contents($dir . '/index.html', $html);
    }

    private function generateCSS($module, $dir)
    {
        $css = <<<CSS
        .module {
            width: {$module->width}px;
            height: {$module->height}px;
            background-color: {$module->color};
            cursor: pointer;
        }
        CSS;

        file_put_contents($dir . '/styles.css', $css);
    }

    private function generateJS($module, $dir)
    {
                $js = <<<JS
        document.querySelector('.module').addEventListener('click', () => {
            window.open('{$module->link}', '_blank');
        });
        JS;

        file_put_contents($dir . '/script.js', $js);
    }
}
