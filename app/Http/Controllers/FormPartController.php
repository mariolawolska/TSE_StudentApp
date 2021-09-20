<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormPartController extends SecureController {

    public function formPart(string $flow) {

        $this->formStageControll($flow);

        return redirect()->back();
    }

    public function takeStep(Request $request) {

        $form_stage = \Session::get('form_stage');

        $form_stage['stage_no'] = (int) $request->input("no");

        if ($form_stage['stage_no'] > 1) {
            $form_stage['show_back'] = true;
        } else {
            $form_stage['show_back'] = false;
        }

        $form_stage['show_next'] = false;
        if (
                ($form_stage['stage_no'] < 9 && array_key_exists($form_stage['stage_no'], $form_stage['steps_available']))
        ) {
            $form_stage['show_next'] = true;
        }

        \Session::put('form_stage', $form_stage);

        return redirect()->back();
    }

}
