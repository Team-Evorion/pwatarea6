@props(['status'])
@php
 $labels=['Pending'=>'Pendiente','Preparing'=>'En preparación','Ready to serve'=>'Listo para servir','Served'=>'Servido'];
 $classes=['Pending'=>'pending','Preparing'=>'preparing','Ready to serve'=>'ready','Served'=>'served'];
@endphp
<span class="pill pill-{{ $classes[$status]??'pending' }}">{{ $labels[$status]??$status }}</span>
