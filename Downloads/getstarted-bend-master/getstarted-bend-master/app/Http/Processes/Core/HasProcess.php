<?php

namespace App\Http\Processes\Core;
use Illuminate\Http\Request;

trait HasProcess{
    protected $process;
    protected $model;

    public function options($status_id = 1)
    {
        return response()->json($this->process->nextOptions($status_id));
    }

    public function execute(Request $request)
    {
        if (isset($request['id'])) {
            $data = $this->model::find($request['id']);
            $this->process->setObject($data);
        } else
            $this->process->setObject();

        $result = $this->process->executeAction($request['action'], $request);

        return response()->json($result);
    }

}
