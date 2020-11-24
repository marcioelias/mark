<?php

namespace App\Http\Controllers;

use App\Models\User\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailTemplateController extends Controller
{
    public function store(Request $request) {
        if ($request->images) {
            $mailTemplate = MailTemplate::create([
                'template_name' => $request->template_name,
                'template' => ' '
            ]);

            $template = $request->template;
            foreach ($request->images as $image) {
                $mailTemplate->addMediaFromBase64($image)->toMediaCollection('mail-images');
                $mediaItems = $mailTemplate->load('media')->getMedia('mail-images');
                $template = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $template);
            }

            $mailTemplate->template = $template;
        } else {
            $mailTemplate = new MailTemplate($request->all());
        }

        $mailTemplate->save();

        return response()->json($mailTemplate);
    }

    public function templateExists(Request $request) {
        $exists = MailTemplate::where('template_name', $request->template_name)->count() > 0;
        return response()->json(['exists' => $exists]);
    }

    public function listTemplates() {
        $templates = MailTemplate::select('id', 'template_name')
                                ->orderBy('template_name', 'ASC')
                                ->get();

        return response()->json($templates);
    }

    public function loadTemplate(MailTemplate $mailTemplate) {
        return response()->json($mailTemplate);
    }
}
