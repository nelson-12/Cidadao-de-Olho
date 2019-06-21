<?php

namespace Modules\Deputados\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Client\Client;
use Entities\Deputado;
use DB;


class DeputadosController extends Controller
{
    protected $client,$deputado;

	public function __construct(Client $client)
	{
        $this->client = $client;
	}

    public function index($id)
    {   
        //Realiza a busca se existe o ano de mandato já salvo no banco;
        if(DB::table('Deputado')->where('anoMandato', $id)->exists()){
            $data = ['Deputados' => DB::table('Deputado')->where('anoMandato', $id)->get()];
	        return response()->json($data);
        }
        try{
            //realiza a pesquisa da api
            $url = "http://dadosabertos.almg.gov.br/ws/legislaturas/".$id."/deputados/situacao/1?formato=json";
            $result = $this->client->requestGet($url);
            $dataSet = [];
            foreach ($result->list as $safety) {
                $dataSet[] = [
                    'Nome'  => $safety->nome,
                    'Partido'    => $safety->partido,
                    'Identificador'  => $safety->id,
                    'anoMandato' => $id,
                ];
            }
            DB::table('Deputado')->insert($dataSet);
            $data = ['Deputados' => DB::table('Deputado')->where('anoMandato', $id)->get()];
	        return response()->json($data);
        }catch(\Exception $e){
            return response()->json(['error' => $result->status->nome]);
        }
       
    }

    public function gastos($id,$mes)
    {
        if(DB::table('Deputado')->where('anoMandato', $id)->exists()){
           if(!DB::table('Gastos')->where('mes', $mes)->exists()){
                $dataSet = [];
                foreach (DB::table('Deputado')->where('anoMandato', $id)->cursor() as $deputado) {
                        $url = "http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/".$deputado->Identificador."/20".$id."/".$mes."?formato=json";
                        $response =  $this->client->requestGetInde($url); 
                        if(!empty($response->list))
                        {
                            foreach($response->list as $list){
                                $dataSet[] = [
                                    'Identificador'  => $list->idDeputado,
                                    'Valor'    => $list->valor,
                                    'mes' => $mes,
                                ];
                            }
                        }
                }
                DB::table('Gastos')->insert($dataSet);
                $this->gastos($id,$mes);
           }
              $dataSet = [];
               foreach(DB::table('Deputado')->where('anoMandato',$id)->cursor() as $deputado){
                   $valor = 0;
                   foreach(DB::table('Gastos')->where('Identificador',$deputado->Identificador)->cursor() as $gasto){      
                    if($gasto->mes == $mes){
                       $valor+= $gasto->Valor; 
                    }
                   }
                   $dataSet[] = [
                    'Identificador'  => $deputado->Identificador,
                    'Nome'    => $deputado->Nome,
                    'Valor' => $valor,
                   ];
                } 
               
                usort($dataSet, function ($a, $b) {
                    return $a['Valor'] <=> $b['Valor'];
                });
                $data = ['Top 5 Maiores verbas indenizatorias por mes' => array_slice(array_reverse($dataSet), 0, 5, true)];
                return response()->json($data);
            
        }else{
            $data = ['Error' =>'Nenhum Deputado Cadastrado'];
            return response()->json($data);
        }
    }

    public function Midia()
    {
        
        $redes = DB::table('redeSociais')->count();
        if($redes == 0){
            $dataSet = [];
            $url="http://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json";
            $result = $this->client->requestGet($url);
            foreach ($result->list as $safety) {
                foreach ($safety->redesSociais as $rede) {
                    $dataSet[] = [
                        'Identificador'  => $rede->redeSocial->id,
                        'Nome'    => $rede->redeSocial->nome,
                    ];
                }
            }
            DB::table('redeSociais')->insert($dataSet);
            $this->delete();
         }
            $dataSet = [];
            foreach(DB::table('redeSociais')->select(DB::raw('count(*) as rede_count, Identificador,nome'))->groupBy('nome')->cursor() as $rede){
                $dataSet[] = [
                    'Nome'  => $rede->nome,
                    'Quantidade'    => $rede->rede_count,
                ];
            }
            usort($dataSet, function ($a, $b) {
                return $a['Quantidade'] <=> $b['Quantidade'];
            });
            $data = ['Top Rede Sociais' => array_reverse($dataSet)];
            return response()->json($data);
        
    }

   
}
