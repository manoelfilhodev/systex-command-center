<?php

namespace Database\Seeders;

use App\Models\Servico;
use Illuminate\Database\Seeder;

class ServicosSeeder extends Seeder
{
    public function run(): void
    {
        $servicos = [
            [
                'nome' => 'WMS',
                'categoria' => 'wms',
                'tipo_receita' => 'hibrida',
                'valor_base' => 0,
                'descricao' => 'Sistema de gestão de armazém, estoque, inventário, separação, armazenagem e operação logística.',
                'ativo' => true,
            ],
            [
                'nome' => 'ERP',
                'categoria' => 'erp',
                'tipo_receita' => 'hibrida',
                'valor_base' => 0,
                'descricao' => 'Sistema de gestão empresarial com módulos administrativos, financeiros e operacionais.',
                'ativo' => true,
            ],
            [
                'nome' => 'CRM',
                'categoria' => 'crm',
                'tipo_receita' => 'hibrida',
                'valor_base' => 0,
                'descricao' => 'Sistema para gestão comercial, pipeline de vendas, clientes, propostas e contratos.',
                'ativo' => true,
            ],
            [
                'nome' => 'Desenvolvimento Sob Demanda',
                'categoria' => 'desenvolvimento_sob_demanda',
                'tipo_receita' => 'unica',
                'valor_base' => 0,
                'descricao' => 'Desenvolvimento personalizado de sistemas, automações e soluções específicas para o cliente.',
                'ativo' => true,
            ],
            [
                'nome' => 'Implantação',
                'categoria' => 'implantacao',
                'tipo_receita' => 'unica',
                'valor_base' => 0,
                'descricao' => 'Serviço de implantação, configuração inicial, treinamento e go live.',
                'ativo' => true,
            ],
            [
                'nome' => 'Suporte Mensal',
                'categoria' => 'suporte',
                'tipo_receita' => 'recorrente',
                'valor_base' => 0,
                'descricao' => 'Sustentação, suporte técnico, acompanhamento e evolução contínua.',
                'ativo' => true,
            ],
        ];

        foreach ($servicos as $servico) {
            Servico::updateOrCreate(
                ['nome' => $servico['nome']],
                $servico
            );
        }
    }
}
