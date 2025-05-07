<?php

namespace App\Services\Onsite;

use App\Models\Perfil;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\PerfilUsuario;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;

class UserService
{
    protected $userCompanyId;

    public function __construct()
    {
        //$this->userCompanyId = Session::get('userCompanyIdDefault');
        $this->userCompanyId = 2;
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

    public function store()
    {
    }

    public function update()
    {
    }

    public function destroy($id)
    {
    }

    public function filtrar(Request $request)
    {
    }
    public function generarCsv($texto, $idReparacion, $idEstado, $idUsuario, $visibilidad)
    {
    }

    public function getUsers()
    {
        $users_company = UserCompany::where('company_id', $this->userCompanyId)
            ->get();

        return $users_company;
    }

    public function getUsersOnsite($company_id)
    {
        $users_company = UserCompany::where('company_id', $company_id)
            ->pluck('user_id')
            ->toArray();

        $users = User::whereIn('id', $users_company)
            ->get();

        return $users;
    }

    public function getTecnicos($company_id)
    {
        $users_perfil = PerfilUsuario::where('id_perfil', 11)
            ->pluck('id_usuario')
            ->toArray();

        $users_company = UserCompany::where('company_id', $company_id)
            ->whereIn('user_id', $users_perfil)
            ->pluck('user_id')
            ->toArray();

        $users = User::whereIn('id', $users_company)
            ->get();

        return $users;
    }

    public function findUser($idUser)
    {
        $user = User::with([
            'empresa_instaladora',
            'perfil_usuario' => function ($query) {
                $query->orderBy('created_at', 'desc')->first();
            }
        ])->find($idUser);

        return $user;
    }

    public function findEmpresaUser($idUser)
    {
        $user = User::with(['empresas_onsite' => function ($query) {
            $query->orderBy('created_at', 'desc')
                ->first();
        }])
            ->find($idUser);



        return $user;
    }

    public function getVisitasPorTecnico()
    {
        $tecnicos = User::with(['reparacion_onsite' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->with(['companies' => function ($query) {
                $query->where('company_id', $this->userCompanyId);
            }])
            /* ->where('company_id', $this->userCompanyId) */
            ->limit(40)
            ->get();



        return $tecnicos;
    }

    public function getUserById($id)
    {
        $user = User::where('id', $id)
            ->first();

        return $user;
    }

    public static function listarTecnicosOnsite($userCompanyId)
	{
		return DB::table('users')
			->join('perfiles_usuarios', 'perfiles_usuarios.id_usuario', '=', 'users.id')
			->join('user_companies', 'user_companies.user_id', '=', 'users.id')
			->select('users.*')

			->where('user_companies.company_id', $userCompanyId)

			->where('perfiles_usuarios.id_perfil', Perfil::TECNICO_ONSITE)
			->orderBy('users.name', 'asc')
			->pluck('users.name', 'users.id');
	}
}
