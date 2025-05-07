<?php

namespace App\Services\Onsite;

use App\Models\TemplateComprobante;

class TemplatesService
{
    protected $userCompanyId;

    public function __construct()
    {
        $this->userCompanyId =  session()->get('userCompanyIdDefault');
    }
    public function getDataList()
    {
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function store($request)
    {

        

    }

    public function update()
    {
    }

    public function destroy($id)
    {
    }

    public function getTemplate($id)
    {
        $template = TemplateComprobante::find($id);

        return $template;
    }

   
    

   
}
