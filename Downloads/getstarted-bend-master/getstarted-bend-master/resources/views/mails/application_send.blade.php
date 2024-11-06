@component('mail::message')
<span style="font-weight:bold; color:black;">Orden de Servicio</span>

<br />
@if(isset($message['solicitante']) )
<span style="font-weight:bold; color:black;">Solicitante:</span> {{ $message['solicitante'] }}<br>
@endif

<span style="font-weight:bold; color:black;">Estatus:</span> {{ $message['estatus'] }}<br>
{{ $message['message'] }}<br>

@if(isset($message['configName']) && ($message['configName'] == "MA_CREATED_MESSAGE_EMAIL" || $message['configName'] == "MA_ASSIGN_MESSAGE_EMAIL") )
<br>
<span style="font-weight:bold; color:black;">Reporte:</span><br>
{{ $message['reporte'] }}
<br>
<span style="font-weight:bold; color:black;">Observaciones:</span><br>
{{ $message['observaciones'] }}
@endif

@if(isset($message['configName']) && $message['configName'] == "MA_FINISH_MESSAGE_EMAIL" )
<br>
@if(isset($message['recibe']))
<span style="font-weight:bold; color:black;">Persona Recibe:</span> {{ $message['recibe'] }}
@endif

<span style="font-weight:bold; color:black;">Observaciones:</span><br>
{{ $message['observaciones'] }}
@endif
@endcomponent