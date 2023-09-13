<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitante;
use App\Models\Ingreso;
use App\Models\Equipo;
use App\Models\Personal;
use App\Models\Automovil;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreIngresos;
use App\Http\Requests\StoreEquipos;
use App\Http\Requests\StoreAutos;

header("Content-type: image/png");

/* require_once __DIR__ .  '/phpqrcode/qrlib.php' ;
require_once __DIR__ .  '/phpqrcode/qrconfig.php' ;
require_once __DIR__ .  '/phpqrcode/config.php' ; */
include '../resources/phpqrcode/qrlib.php';
use QRcode;
class IngresosController extends Controller
{
    

    public function store(StoreIngresos $request)
    {
        try {
            $visitante = Visitante::create([
                'nombre' => $request->nombre,
                'procedencia' => $request->procedencia,
                'asunto' =>  $request->asunto,
                'contacto' => $request->contacto,
            ]);

            $u_id =(uniqid() . $request->personal_id . $visitante->id);
            $algo = $this->generateQR($u_id);

            $ingreso = Ingreso::create([
                'fecha' => $request->fecha,
                'hora_agendada' => $request->hora_agendada,
                'edo_cita' => true,
                'codigo' => $u_id,
                'codigo_qr' => $algo,
                'visitante_id' => $visitante->id,
                'personal_id' => $request->personal_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Registro exitoso',
                'datos_visitante' => $visitante,
                'datos_ingreso' => $ingreso,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $e,
            ]);
        }
    }

    public function generateQR($u_id) { 
        
        $_file="";

        if(class_exists('QRcode'))
        {
            
            $tempDir = "../resources/images/";
            $codeContents = $u_id;
            $fileName = $u_id.'.png';
            $pngAbsoluteFilePath = $tempDir.$fileName;
            $urlRelativeFilePath = "images/".$fileName;
            
            if (!file_exists($pngAbsoluteFilePath)) {
                QRcode::png($codeContents, $pngAbsoluteFilePath,10,10);
            } 
    
        $_file = $fileName;
    
    }else{
        
        $_file = 'not_file.png';
    }
    
    return $_file;
    
    }

    public function store_equipo(StoreEquipos $request)
    {
        try {

            $ingreso_id = $this->buscaIdCodIngreso($request->cod_ingreso);

            if ($ingreso_id != null) {
                $equipo = Equipo::create([
                    'marca' => $request->marca,
                    'modelo' => $request->modelo,
                    'no_serie' =>  $request->no_serie,
                    'ingreso_id' => $ingreso_id->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro Exitoso',
                    'datos_equipo' => $equipo,
                    'datos_visitante' => Visitante::find($ingreso_id->visitante_id),
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No existe el código de seguimiento.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ]);
        }
    }

    public function store_automovil(StoreAutos $request)
    {
        try {
            $ingreso_id = $this->buscaIdCodIngreso($request->cod_ingreso);

            if ($ingreso_id != null) {

                $auto = Automovil::create([
                    'marca' => $request->marca,
                    'color' => $request->color,
                    'placas' =>  $request->placas,
                    'ingreso_id' => $ingreso_id->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro Exitoso',
                    'datos_auto' => $auto,
                    'datos_visitante' => Visitante::find($ingreso_id->visitante_id),
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No existe el código de seguimiento.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $e,
            ]);
        }
    }

    public function read($cod_ingreso = null)
    {
        if ($cod_ingreso != null) {

            $ingreso_id = $this->buscaIdCodIngreso($cod_ingreso);

            $ingreso = Ingreso::find($ingreso_id->id);
            $visitante =  Visitante::find($ingreso->visitante_id);
            $personal = Personal::find($ingreso->personal_id);

            //Contruye el JSON con los datos del personal que se va a visitar.
            $arr_datos_personal = [
                'id' => $personal->id,
                'nombre' => ($personal->nombre . " " . $personal->ap_paterno . " " . $personal->ap_materno),
                'area' => $personal->area,
                'extension_telefonica' => $personal->extension
            ];
            $datos_personal = json_encode($arr_datos_personal);

            //Construye el JSON con los datos del ingreso 
            $arr_datos_ingreso = [
                'id' => $ingreso->id,
                'fecha_cita' => $ingreso->fecha,
                'hora_agendada' => $ingreso->hora_agendada,
                'hora_entrada' => $ingreso->hora_entrada,
                'hora_salida' => $ingreso->hora_salida,
                'edo_cita' => ($ingreso->edo_cita == 1 ? true : false),
                'codigo' => $ingreso->codigo,
                'cod_qr' => $ingreso->codigo_qr
            ];
            $datos_ingreso = json_encode($arr_datos_ingreso);


            //Asigna la hora capturada por el servidor para el ingreso.
            date_default_timezone_set('America/Mexico_City');
            $ingreso->hora_entrada = date("h:i:s");


            //Obtiene los datos del equipo registrado para la visita.
            $datos_equipo = DB::table('equipos')
                ->where('ingreso_id', $ingreso->id)
                ->get();

            //Obtiene los datos del auto registrado para la visita.
            $datos_auto = DB::table('autos')
                ->where('ingreso_id', $ingreso->id)
                ->get();


            if ($ingreso != null) {

                return response()->json([
                    'status' => 'success',
                    'message' => 'Record obteined successfully',
                    'datos_ingreso' => json_decode($datos_ingreso),
                    'datos_visitante' => $visitante,
                    'datos_personal' => json_decode($datos_personal),
                    'datos_equipo' => $datos_equipo,
                    'datos_auto' => $datos_auto,

                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter /{id?} was expected',
            ], 401);
        }
    }

    //Leer todas las citas 
    public function readall()
    {

        return response()->json([
            'status' => 'success',
            'message' => 'All records obteined successfully',
            'tamano' => sizeof(Ingreso::all()),
            'datos_ingresos' => Ingreso::all(),
        ]);
    }

    public function buscaIdCodIngreso($cod_ingreso)
    {
        $ingreso_id = DB::table('ingresos')
            ->where('codigo', $cod_ingreso)
            ->first();
        return $ingreso_id;
    }
}

