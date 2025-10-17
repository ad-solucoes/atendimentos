<?php

declare(strict_types = 1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        //Redefinir funções e permissões em cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //criar permissões

        Permission::create(['name' => 'Listar Setor']);
        Permission::create(['name' => 'Adicionar Setor']);
        Permission::create(['name' => 'Editar Setor']);
        Permission::create(['name' => 'Exibir Setor']);
        Permission::create(['name' => 'Deletar Setor']);

        Permission::create(['name' => 'Listar Tipo de Procedimento']);
        Permission::create(['name' => 'Adicionar Tipo de Procedimento']);
        Permission::create(['name' => 'Editar Tipo de Procedimento']);
        Permission::create(['name' => 'Exibir Tipo de Procedimento']);
        Permission::create(['name' => 'Deletar Tipo de Procedimento']);

        Permission::create(['name' => 'Listar Procedimento']);
        Permission::create(['name' => 'Adicionar Procedimento']);
        Permission::create(['name' => 'Editar Procedimento']);
        Permission::create(['name' => 'Exibir Procedimento']);
        Permission::create(['name' => 'Deletar Procedimento']);

        Permission::create(['name' => 'Listar Equipe de Saude']);
        Permission::create(['name' => 'Adicionar Equipe de Saude']);
        Permission::create(['name' => 'Editar Equipe de Saude']);
        Permission::create(['name' => 'Exibir Equipe de Saude']);
        Permission::create(['name' => 'Deletar Equipe de Saude']);

        Permission::create(['name' => 'Listar Agente de Saude']);
        Permission::create(['name' => 'Adicionar Agente de Saude']);
        Permission::create(['name' => 'Editar Agente de Saude']);
        Permission::create(['name' => 'Exibir Agente de Saude']);
        Permission::create(['name' => 'Deletar Agente de Saude']);

        Permission::create(['name' => 'Listar Paciente']);
        Permission::create(['name' => 'Adicionar Paciente']);
        Permission::create(['name' => 'Editar Paciente']);
        Permission::create(['name' => 'Exibir Paciente']);
        Permission::create(['name' => 'Deletar Paciente']);

        Permission::create(['name' => 'Listar Atendimento']);
        Permission::create(['name' => 'Adicionar Atendimento']);
        Permission::create(['name' => 'Editar Atendimento']);
        Permission::create(['name' => 'Exibir Atendimento']);
        Permission::create(['name' => 'Deletar Atendimento']);

        Permission::create(['name' => 'Listar Solicitacao']);
        Permission::create(['name' => 'Exibir Solicitacao']);
        Permission::create(['name' => 'Movimentar Solicitacao']);

        Permission::create(['name' => 'Realizar Movimentacao']);
        Permission::create(['name' => 'Historico Movimentacao']);

        Permission::create(['name' => 'Listar Permissao']);
        Permission::create(['name' => 'Adicionar Permissao']);
        Permission::create(['name' => 'Editar Permissao']);
        Permission::create(['name' => 'Exibir Permissao']);
        Permission::create(['name' => 'Deletar Permissao']);

        Permission::create(['name' => 'Listar Perfil']);
        Permission::create(['name' => 'Adicionar Perfil']);
        Permission::create(['name' => 'Editar Perfil']);
        Permission::create(['name' => 'Exibir Perfil']);
        Permission::create(['name' => 'Deletar Perfil']);
        Permission::create(['name' => 'Gerenciar Permissao']);

        Permission::create(['name' => 'Listar Usuario']);
        Permission::create(['name' => 'Adicionar Usuario']);
        Permission::create(['name' => 'Editar Usuario']);
        Permission::create(['name' => 'Exibir Usuario']);
        Permission::create(['name' => 'Deletar Usuario']);
        Permission::create(['name' => 'Gerenciar Perfil']);

        Permission::create(['name' => 'Listar Auditoria']);
        Permission::create(['name' => 'Exibir Auditoria']);
        Permission::create(['name' => 'Deletar Auditoria']);

        Permission::create(['name' => 'Listar Log']);
        Permission::create(['name' => 'Exibir Log']);
        Permission::create(['name' => 'Deletar Log']);

        Permission::create(['name' => 'Gerenciar Relatorios']);

        //criar funções e atribuir permissões existentes

        /* Master */
        $role1 = Role::create(['name' => 'Administrador']);

        $role1->givePermissionTo('Listar Permissao');
        $role1->givePermissionTo('Adicionar Permissao');
        $role1->givePermissionTo('Editar Permissao');
        $role1->givePermissionTo('Exibir Permissao');
        $role1->givePermissionTo('Deletar Permissao');

        $role1->givePermissionTo('Listar Perfil');
        $role1->givePermissionTo('Adicionar Perfil');
        $role1->givePermissionTo('Editar Perfil');
        $role1->givePermissionTo('Exibir Perfil');
        $role1->givePermissionTo('Deletar Perfil');
        $role1->givePermissionTo('Gerenciar Permissao');

        $role1->givePermissionTo('Listar Usuario');
        $role1->givePermissionTo('Adicionar Usuario');
        $role1->givePermissionTo('Editar Usuario');
        $role1->givePermissionTo('Exibir Usuario');
        $role1->givePermissionTo('Deletar Usuario');
        $role1->givePermissionTo('Gerenciar Perfil');

        $role1->givePermissionTo('Listar Auditoria');
        $role1->givePermissionTo('Exibir Auditoria');
        $role1->givePermissionTo('Deletar Auditoria');

        $role1->givePermissionTo('Listar Log');
        $role1->givePermissionTo('Exibir Log');
        $role1->givePermissionTo('Deletar Log');

        $role1->givePermissionTo('Gerenciar Relatorios');

        /* Recepcao */
        $role2 = Role::create(['name' => 'Recepcao']);

        $role2->givePermissionTo('Listar Setor');
        $role2->givePermissionTo('Adicionar Setor');
        $role2->givePermissionTo('Editar Setor');
        $role2->givePermissionTo('Exibir Setor');
        $role2->givePermissionTo('Deletar Setor');

        $role2->givePermissionTo('Listar Tipo de Procedimento');
        $role2->givePermissionTo('Adicionar Tipo de Procedimento');
        $role2->givePermissionTo('Editar Tipo de Procedimento');
        $role2->givePermissionTo('Exibir Tipo de Procedimento');
        $role2->givePermissionTo('Deletar Tipo de Procedimento');

        $role2->givePermissionTo('Listar Procedimento');
        $role2->givePermissionTo('Adicionar Procedimento');
        $role2->givePermissionTo('Editar Procedimento');
        $role2->givePermissionTo('Exibir Procedimento');
        $role2->givePermissionTo('Deletar Procedimento');

        $role2->givePermissionTo('Listar Equipe de Saude');
        $role2->givePermissionTo('Adicionar Equipe de Saude');
        $role2->givePermissionTo('Editar Equipe de Saude');
        $role2->givePermissionTo('Exibir Equipe de Saude');
        $role2->givePermissionTo('Deletar Equipe de Saude');

        $role2->givePermissionTo('Listar Agente de Saude');
        $role2->givePermissionTo('Adicionar Agente de Saude');
        $role2->givePermissionTo('Editar Agente de Saude');
        $role2->givePermissionTo('Exibir Agente de Saude');
        $role2->givePermissionTo('Deletar Agente de Saude');

        $role2->givePermissionTo('Listar Paciente');
        $role2->givePermissionTo('Adicionar Paciente');
        $role2->givePermissionTo('Editar Paciente');
        $role2->givePermissionTo('Exibir Paciente');
        $role2->givePermissionTo('Deletar Paciente');

        $role2->givePermissionTo('Listar Atendimento');
        $role2->givePermissionTo('Adicionar Atendimento');
        $role2->givePermissionTo('Editar Atendimento');
        $role2->givePermissionTo('Exibir Atendimento');
        $role2->givePermissionTo('Deletar Atendimento');

        $role2->givePermissionTo('Listar Solicitacao');
        $role2->givePermissionTo('Exibir Solicitacao');
        $role2->givePermissionTo('Movimentar Solicitacao');

        $role2->givePermissionTo('Realizar Movimentacao');
        $role2->givePermissionTo('Historico Movimentacao');

        $role2->givePermissionTo('Gerenciar Relatorios');

        /* Marcacao */
        $role3 = Role::create(['name' => 'Marcacao']);

        $role3->givePermissionTo('Listar Setor');
        $role3->givePermissionTo('Adicionar Setor');
        $role3->givePermissionTo('Editar Setor');
        $role3->givePermissionTo('Exibir Setor');
        $role3->givePermissionTo('Deletar Setor');

        $role3->givePermissionTo('Listar Tipo de Procedimento');
        $role3->givePermissionTo('Adicionar Tipo de Procedimento');
        $role3->givePermissionTo('Editar Tipo de Procedimento');
        $role3->givePermissionTo('Exibir Tipo de Procedimento');
        $role3->givePermissionTo('Deletar Tipo de Procedimento');

        $role3->givePermissionTo('Listar Procedimento');
        $role3->givePermissionTo('Adicionar Procedimento');
        $role3->givePermissionTo('Editar Procedimento');
        $role3->givePermissionTo('Exibir Procedimento');
        $role3->givePermissionTo('Deletar Procedimento');

        $role3->givePermissionTo('Listar Equipe de Saude');
        $role3->givePermissionTo('Adicionar Equipe de Saude');
        $role3->givePermissionTo('Editar Equipe de Saude');
        $role3->givePermissionTo('Exibir Equipe de Saude');
        $role3->givePermissionTo('Deletar Equipe de Saude');

        $role3->givePermissionTo('Listar Agente de Saude');
        $role3->givePermissionTo('Adicionar Agente de Saude');
        $role3->givePermissionTo('Editar Agente de Saude');
        $role3->givePermissionTo('Exibir Agente de Saude');
        $role3->givePermissionTo('Deletar Agente de Saude');

        $role3->givePermissionTo('Listar Paciente');
        $role3->givePermissionTo('Adicionar Paciente');
        $role3->givePermissionTo('Editar Paciente');
        $role3->givePermissionTo('Exibir Paciente');
        $role3->givePermissionTo('Deletar Paciente');

        $role3->givePermissionTo('Listar Atendimento');
        $role3->givePermissionTo('Adicionar Atendimento');
        $role3->givePermissionTo('Editar Atendimento');
        $role3->givePermissionTo('Exibir Atendimento');
        $role3->givePermissionTo('Deletar Atendimento');

        $role3->givePermissionTo('Listar Solicitacao');
        $role3->givePermissionTo('Exibir Solicitacao');
        $role3->givePermissionTo('Movimentar Solicitacao');

        $role3->givePermissionTo('Realizar Movimentacao');
        $role3->givePermissionTo('Historico Movimentacao');

        $role3->givePermissionTo('Gerenciar Relatorios');

        /* Gestor */
        $role4 = Role::create(['name' => 'Gestor']);

        $role4->givePermissionTo('Listar Setor');
        $role4->givePermissionTo('Exibir Setor');

        $role4->givePermissionTo('Listar Tipo de Procedimento');
        $role4->givePermissionTo('Exibir Tipo de Procedimento');

        $role4->givePermissionTo('Listar Procedimento');
        $role4->givePermissionTo('Exibir Procedimento');

        $role4->givePermissionTo('Listar Equipe de Saude');
        $role4->givePermissionTo('Exibir Equipe de Saude');

        $role4->givePermissionTo('Listar Agente de Saude');
        $role4->givePermissionTo('Exibir Agente de Saude');

        $role4->givePermissionTo('Listar Paciente');
        $role4->givePermissionTo('Exibir Paciente');

        $role4->givePermissionTo('Listar Atendimento');
        $role4->givePermissionTo('Exibir Atendimento');

        $role4->givePermissionTo('Listar Solicitacao');
        $role4->givePermissionTo('Exibir Solicitacao');

        $role4->givePermissionTo('Gerenciar Relatorios');

        $user  = new \App\Models\User();
        $user1 = $user->where('id', 1)->first();
        $user1->assignRole([$role1]);

        $user2 = $user->where('id', 2)->first();
        $user2->assignRole([$role2]);

        $user3 = $user->where('id', 3)->first();
        $user3->assignRole([$role3]);

        $user4 = $user->where('id', 4)->first();
        $user4->assignRole([$role4]);
    }
}
