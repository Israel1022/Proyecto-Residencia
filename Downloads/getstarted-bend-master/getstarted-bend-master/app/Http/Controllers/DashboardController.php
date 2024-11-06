<?php

namespace App\Http\Controllers;

use App\Models\Empresa\Configuracion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class DashboardController extends Controller
{


    public function graficas(Request $request)
    {
        try {

            $graf1 = [];
            $graf2 = [];
            $graf3 = [];
            $tableUser = [];
            $dateFinal = '';
            $dateInit = '';
            $dafault = 'no';
            if (isset($request->fInicial) && isset($request->ffinal)) {
                $dateFinal = $request->ffinal;
                $dateInit = $request->fInicial;
            } else {
                $config_graf = Configuracion::where('nombre', 'GRAFICAS_DASHBOARD')->first();
                $dias = ($config_graf->descripcion['rango']) * -1;
                $dateFinal =  date("Y-m-d");
                $date = strtotime("{$dias} day", strtotime($dateFinal));
                $dateInit = date('Y-m-d', $date);
                $dafault = 'si';
            }

            $TipoActividad = $this->tipoActividad($dateFinal, $dateInit);
            foreach ($TipoActividad as $tipoActividad) {
                if ($tipoActividad->tipo_actividad == 'Mesa Ayuda') {
                    array_push($graf1, ['label' => $tipoActividad->estatus, 'value' => $tipoActividad->total]);
                } else {
                    array_push($graf2, ['label' => $tipoActividad->estatus, 'value' => $tipoActividad->total]);
                }
            }

            $tableUser = $this->tipoActividadByUsuario();
            $tipoAtencionGral = $this->tipoAtencionGral($dateFinal, $dateInit);
            $tipoActividadEstatusActvo = $this->tipoActividadEstatusActvo();
            $indicador = ["MASolictud" => 0, "MAActivos" => 0, "SEEntrada" => 0, "SEActivos" => 0];
            foreach ($tipoActividadEstatusActvo as $ActStatusActivo) {
                if ($ActStatusActivo->tipo_actividad == 'Mesa Ayuda') {
                    if ($ActStatusActivo->status == 'Solicitado')
                        $indicador['MASolictud'] = $indicador['MASolictud'] + $ActStatusActivo->total;
                    else
                        $indicador['MAActivos'] = $indicador['MAActivos'] + $ActStatusActivo->total;
                } else if ($ActStatusActivo->tipo_actividad == 'Servicio Equipos') {
                    if ($ActStatusActivo->status == 'EntradaServicio')
                        $indicador['SEEntrada'] = $indicador['SEEntrada'] + $ActStatusActivo->total;
                    else
                        $indicador['SEActivos'] = $indicador['SEActivos'] + $ActStatusActivo->total;
                }
            }

            $graf3 = $this->tipoEstatusEquipo($dateFinal, $dateInit);

            $graficas = [
                'graf1' => $graf1, 'graf2' => $graf2, 'tableUser' => $tableUser, 'tablestatus' => $graf3,
                'dafault' => $dafault, 'init' => $dateInit, 'final' => $dateFinal,
                'tipoAtencion' => $tipoAtencionGral,
                'indicadorEstatusActivos' => $indicador
            ];

            return response($graficas);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()]);
        }
    }

    private function tipoActividad($dateFinal, $dateInit)
    {
        $actividades = DB::select("SELECT ta.nombre AS tipo_actividad,est.estatus,COUNT(est.estatus) As total
            FROM sts_meayu_mesa_ayuda ma
            INNER JOIN sts_emp_tipos_actividades ta ON ma.tipo_actividade_id = ta.id
            INNER JOIN ( SELECT MAX(m.id) AS id,m.folio FROM sts_proces_movimientos m GROUP BY m.folio) umov ON ma.folio = umov.folio
            INNER JOIN sts_proces_movimientos mov ON umov.id = mov.id
            INNER JOIN sts_proces_estatus est ON mov.estatus_id = est.id
            WHERE ma.activo ='si' AND ma.created_at >= '" . $dateInit . "' AND ma.created_at <= '" . $dateFinal . "'
            GROUP BY ta.nombre,est.estatus");
        return $actividades;
    }
    private function tipoActividadByUsuario()
    {
        $usuarios = DB::select("SELECT u.empleado_id, emp.cve_empleado, u.usuario, CONCAT(emp.nombres,' ',emp.apellidos) AS nombrecompleto, emp.departamento_id, d.nombre AS departamento
                    FROM sts_emp_usuarios u
                        INNER JOIN sts_emp_empleados emp ON emp.id = u.empleado_id
                        INNER JOIN sts_emp_departamentos d ON d.id = emp.departamento_id");
        $config = Configuracion::where('nombre', 'MESA_AYUDA_SOLICITUDES')->first();
        $asignados = $config->descripcion['asignados'];
        foreach ($usuarios as $users) {
            $solicitudes = DB::select("SELECT CONCAT(e.nombres,' ',e.apellidos) AS nombrecompleto, u.usuario, ta.nombre AS tipo_actividad, COUNT(ta.nombre) AS total
                FROM sts_meayu_mesa_ayuda ma
                    INNER JOIN sts_emp_tipos_actividades ta ON ma.tipo_actividade_id = ta.id
                    INNER JOIN (SELECT MAX(m.id) AS id,m.folio FROM sts_proces_movimientos m GROUP BY m.folio) umov ON ma.folio = umov.folio
                    INNER JOIN sts_proces_movimientos mov ON umov.id = mov.id
                    LEFT JOIN sts_proces_detalles det ON det.movimiento_id = umov.id
                    LEFT JOIN sts_emp_empleados e ON e.id =  JSON_EXTRACT(det.descripcion,'$.empleado_id')
                    LEFT JOIN sts_emp_departamentos d ON d.id =  e.departamento_id
                    LEFT JOIN sts_emp_usuarios u ON e.id = u.empleado_id
                    WHERE ma.activo = 'si' AND mov.estatus_id NOT IN (5,20) AND e.id = " . $users->empleado_id . "
                    GROUP BY u.usuario,ta.nombre,e.nombres,e.apellidos,d.nombre");
            $users->pendienteMA = 0;
            $users->pendienteSE = 0;
            foreach ($solicitudes as $solicitud) {
                if ($solicitud->tipo_actividad == 'Mesa Ayuda')
                    $users->pendienteMA = $solicitud->total;

                if ($solicitud->tipo_actividad == 'Servicio Equipos')
                    $users->pendienteSE = $solicitud->total;
            }

            $totales = DB::select("SELECT ta.nombre AS tipo_actividad, COUNT(ta.nombre) AS total FROM sts_proces_movimientos m 
                INNER JOIN sts_proces_movimientos mov ON m.id = mov.id
                INNER JOIN sts_meayu_mesa_ayuda ma ON ma.folio = mov.folio
                INNER JOIN sts_emp_tipos_actividades ta ON ma.tipo_actividade_id = ta.id
                LEFT JOIN sts_proces_detalles det ON det.movimiento_id = m.id
                LEFT JOIN sts_emp_usuarios u ON u.empleado_id = JSON_EXTRACT(det.descripcion,'$.empleado_id')
                WHERE mov.estatus_id IN (" . $asignados . ") AND u.empleado_id = " . $users->empleado_id . " 
                GROUP BY  ta.nombre");
            $users->totalMA = 0;
            $users->totalSE = 0;
            foreach ($totales as $solicitud) {
                if ($solicitud->tipo_actividad == 'Mesa Ayuda')
                    $users->totalMA = $solicitud->total;

                if ($solicitud->tipo_actividad == 'Servicio Equipos')
                    $users->totalSE = $solicitud->total;
            }
        }
        return $usuarios;
    }
    private function tipoAtencionGral($dateFinal, $dateInit)
    {
        $tipoAtencion = DB::select("SELECT mta.nombre AS label, COUNT(ma.servicio) AS value  FROM sts_meayu_mesa_ayuda ma 
            INNER JOIN sts_meayu_tipo_atencion mta ON mta.id = ma.servicio
            WHERE ma.activo ='si' AND ma.created_at >= '" . $dateInit . "' AND ma.created_at <= '" . $dateFinal . "' 
            GROUP BY mta.nombre, ma.servicio");
        return $tipoAtencion;
    }
    private function tipoActividadEstatusActvo()
    {
        $tipoEstatus = DB::select("SELECT ta.nombre AS tipo_actividad, sta.estatus AS status, COUNT(sta.nombre) AS total
            FROM sts_meayu_mesa_ayuda ma
            INNER JOIN sts_emp_tipos_actividades ta ON ma.tipo_actividade_id = ta.id
            INNER JOIN (SELECT MAX(m.id) AS id,m.folio FROM sts_proces_movimientos m GROUP BY m.folio) umov ON ma.folio = umov.folio
            INNER JOIN sts_proces_movimientos mov ON umov.id = mov.id
            LEFT JOIN sts_proces_detalles det ON det.movimiento_id = umov.id
            INNER JOIN sts_proces_estatus sta ON sta.id = mov.estatus_id
            WHERE ma.activo = 'si' AND mov.estatus_id NOT IN (5,20)
            GROUP BY ta.nombre, sta.estatus");
        return $tipoEstatus;
    }
    private function tipoEstatusEquipo($dateFinal, $dateInit)
    {
        $comillas = '"';
        $tipoEstatus = DB::select("SELECT REPLACE(JSON_EXTRACT(det.descripcion, '$.estado'), '" . $comillas . "','' ) AS label,
            COUNT(JSON_EXTRACT(det.descripcion, '$.estado')) AS value
            FROM sts_meayu_mesa_ayuda ma
                INNER JOIN sts_emp_tipos_actividades ta ON ma.tipo_actividade_id = ta.id 
                INNER JOIN sts_proces_movimientos mov ON mov.folio = ma.folio 
                INNER JOIN sts_proces_detalles det ON det.movimiento_id = mov.id 
                INNER JOIN sts_proces_estatus sta ON sta.id = mov.estatus_id 
            WHERE ma.tipo_actividade_id= 4 AND ma.activo = 'si' AND mov.estatus_id IN (16) 
            AND ma.created_at >= '" . $dateInit . "' AND ma.created_at <= '" . $dateFinal . "' 
            GROUP BY REPLACE(JSON_EXTRACT(det.descripcion, '$.estado'), '" . $comillas . "','' )");

        return $tipoEstatus;
    }
}
