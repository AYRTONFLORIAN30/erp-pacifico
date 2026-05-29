<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-emerald-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            Nuevo Asiento Contable
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-slate-200">
                
                <div class="bg-slate-800 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-white font-bold text-sm uppercase tracking-wider">Registro de Operación</h3>
                        <p class="text-slate-400 text-xs mt-1">Complete la información del comprobante de pago.</p>
                    </div>
                    <div class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">ERP Pacífico</div>
                </div>

                <div class="p-8">
                    <form action="{{ route('egresos.store') }}" method="POST" id="formCalculadora" x-data="buscadorProveedores()">
                        @csrf

                        <datalist id="lista_rucs">
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->ruc }}">{{ $prov->razon_social }}</option>
                            @endforeach
                        </datalist>

                        <datalist id="lista_razones">
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->razon_social }}">{{ $prov->ruc }}</option>
                            @endforeach
                        </datalist>
                        <div class="mb-8">
                            <h4 class="text-emerald-700 font-bold text-sm uppercase tracking-wide border-b border-emerald-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                Información General
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Emisión *</label>
                                    <input type="date" name="fecha" required class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm transition-all" value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="md:col-span-9">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Comprobante (Tabla 10 SUNAT) *</label>
                                    <select name="descripcion" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm transition-all">
                                        <option value="00 - Otros">00 - Otros</option>
                                        <option value="01 - Factura">01 - Factura</option>
                                        <option value="02 - Recibo por Honorarios">02 - Recibo por Honorarios</option>
                                        <option value="03 - Boleta de Venta">03 - Boleta de Venta</option>
                                        <option value="04 - Liquidación de compra">04 - Liquidación de compra</option>
                                        <option value="05 - Boleto de compañía de aviación comercial por el servicio de transporte aéreo de pasajeros">05 - Boleto de compañía de aviación comercial por el servicio de transporte aéreo de pasajeros</option>
                                        <option value="06 - Carta de porte aéreo por el servicio de transporte de carga aérea">06 - Carta de porte aéreo por el servicio de transporte de carga aérea</option>
                                        <option value="07 - Nota de crédito">07 - Nota de crédito</option>
                                        <option value="08 - Nota de débito">08 - Nota de débito</option>
                                        <option value="09 - Guía de remisión - Remitente">09 - Guía de remisión - Remitente</option>
                                        <option value="10 - Recibo por Arrendamiento">10 - Recibo por Arrendamiento</option>
                                        <option value="11 - Póliza emitida por las Bolsas de Valores, Bolsas de Productos o Agentes de Intermediación por operaciones realizadas en las Bolsas de Valores o Productos o fuera de las mismas, autorizadas por CONASEV">11 - Póliza emitida por las Bolsas de Valores, Bolsas de Productos o Agentes de Intermediación por operaciones realizadas en las Bolsas de Valores o Productos o fuera de las mismas, autorizadas por CONASEV</option>
                                        <option value="12 - Ticket o cinta emitido por máquina registradora">12 - Ticket o cinta emitido por máquina registradora</option>
                                        <option value="13 - Documento emitido por bancos, instituciones financieras, crediticias y de seguros que se encuentren bajo el control de la Superintendencia de Banca y Seguros">13 - Documento emitido por bancos, instituciones financieras, crediticias y de seguros que se encuentren bajo el control de la Superintendencia de Banca y Seguros</option>
                                        <option value="14 - Recibo por servicios públicos de suministro de energía eléctrica, agua, teléfono, telex y telegráficos y otros servicios complementarios que se incluyan en el recibo de servicio público">14 - Recibo por servicios públicos de suministro de energía eléctrica, agua, teléfono, telex y telegráficos y otros servicios complementarios que se incluyan en el recibo de servicio público</option>
                                        <option value="15 - Boleto emitido por las empresas de transporte público urbano de pasajeros">15 - Boleto emitido por las empresas de transporte público urbano de pasajeros</option>
                                        <option value="16 - Boleto de viaje emitido por las empresas de transporte público interprovincial de pasajeros dentro del país">16 - Boleto de viaje emitido por las empresas de transporte público interprovincial de pasajeros dentro del país</option>
                                        <option value="17 - Documento emitido por la Iglesia Católica por el arrendamiento de bienes inmuebles">17 - Documento emitido por la Iglesia Católica por el arrendamiento de bienes inmuebles</option>
                                        <option value="18 - Documento emitido por las Administradoras Privadas de Fondo de Pensiones que se encuentran bajo la supervisión de la Superintendencia de Administradoras Privadas de Fondos de Pensiones">18 - Documento emitido por las Administradoras Privadas de Fondo de Pensiones que se encuentran bajo la supervisión de la Superintendencia de Administradoras Privadas de Fondos de Pensiones</option>
                                        <option value="19 - Boleto o entrada por atracciones y espectáculos públicos">19 - Boleto o entrada por atracciones y espectáculos públicos</option>
                                        <option value="20 - Comprobante de Retención">20 - Comprobante de Retención</option>
                                        <option value="21 - Conocimiento de embarque por el servicio de transporte de carga marítima">21 - Conocimiento de embarque por el servicio de transporte de carga marítima</option>
                                        <option value="22 - Comprobante por Operaciones No Habituales">22 - Comprobante por Operaciones No Habituales</option>
                                        <option value="23 - Pólizas de Adjudicación emitidas con ocasión del remate o adjudicación de bienes por venta forzada, por los martilleros o las entidades que rematen o subasten bienes por cuenta de terceros">23 - Pólizas de Adjudicación emitidas con ocasión del remate o adjudicación de bienes por venta forzada, por los martilleros o las entidades que rematen o subasten bienes por cuenta de terceros</option>
                                        <option value="24 - Certificado de pago de regalías emitidas por PERUPETRO S.A">24 - Certificado de pago de regalías emitidas por PERUPETRO S.A</option>
                                        <option value="25 - Documento de Atribución (Ley del Impuesto General a las Ventas e Impuesto Selectivo al Consumo, Art. 19º, último párrafo, R.S. N° 022-98-SUNAT).">25 - Documento de Atribución (Ley del Impuesto General a las Ventas e Impuesto Selectivo al Consumo, Art. 19º, último párrafo, R.S. N° 022-98-SUNAT).</option>
                                        <option value="26 - Recibo por el Pago de la Tarifa por Uso de Agua Superficial con fines agrarios y por el pago de la Cuota para la ejecución de una determinada obra o actividad acordada por la Asamblea General de la Comisión de Regantes o Resolución expedida por el Jefe de la Unidad de Aguas y de Riego (Decreto Supremo Nº 003-90-AG, Arts. 28 y 48)">26 - Recibo por el Pago de la Tarifa por Uso de Agua Superficial con fines agrarios y por el pago de la Cuota para la ejecución de una determinada obra o actividad acordada por la Asamblea General de la Comisión de Regantes o Resolución expedida por el Jefe de la Unidad de Aguas y de Riego (Decreto Supremo Nº 003-90-AG, Arts. 28 y 48)</option>
                                        <option value="27 - Seguro Complementario de Trabajo de Riesgo">27 - Seguro Complementario de Trabajo de Riesgo</option>
                                        <option value="28 - Tarifa Unificada de Uso de Aeropuerto">28 - Tarifa Unificada de Uso de Aeropuerto</option>
                                        <option value="29 - Documentos emitidos por la COFOPRI en calidad de oferta de venta de terrenos, los correspondientes a las subastas públicas y a la retribución de los servicios que presta">29 - Documentos emitidos por la COFOPRI en calidad de oferta de venta de terrenos, los correspondientes a las subastas públicas y a la retribución de los servicios que presta</option>
                                        <option value="30 - Documentos emitidos por las empresas que desempeñan el rol adquirente en los sistemas de pago mediante tarjetas de crédito y débito">30 - Documentos emitidos por las empresas que desempeñan el rol adquirente en los sistemas de pago mediante tarjetas de crédito y débito</option>
                                        <option value="31 - Guía de Remisión - Transportista">31 - Guía de Remisión - Transportista</option>
                                        <option value="32 - Documentos emitidos por las empresas recaudadoras de la denominada Garantía de Red Principal a la que hace referencia el numeral 7.6 del artículo 7° de la Ley N° 27133 – Ley de Promoción del Desarrollo de la Industria del Gas Natural">32 - Documentos emitidos por las empresas recaudadoras de la denominada Garantía de Red Principal a la que hace referencia el numeral 7.6 del artículo 7° de la Ley N° 27133 – Ley de Promoción del Desarrollo de la Industria del Gas Natural</option>
                                        <option value="34 - Documento del Operador">34 - Documento del Operador</option>
                                        <option value="35 - Documento del Partícipe">35 - Documento del Partícipe</option>
                                        <option value="36 - Recibo de Distribución de Gas Natural">36 - Recibo de Distribución de Gas Natural</option>
                                        <option value="37 - Documentos que emitan los concesionarios del servicio de revisiones técnicas vehiculares, por la prestación de dicho servicio">37 - Documentos que emitan los concesionarios del servicio de revisiones técnicas vehiculares, por la prestación de dicho servicio</option>
                                        <option value="40 - Constancia de Depósito - IVAP (Ley 28211)">40 - Constancia de Depósito - IVAP (Ley 28211)</option>
                                        <option value="50 - Declaración Única de Aduanas - Importación definitiva">50 - Declaración Única de Aduanas - Importación definitiva</option>
                                        <option value="52 - Despacho Simplificado - Importación Simplificada">52 - Despacho Simplificado - Importación Simplificada</option>
                                        <option value="53 - Declaración de Mensajería o Courier">53 - Declaración de Mensajería o Courier</option>
                                        <option value="54 - Liquidación de Cobranza">54 - Liquidación de Cobranza</option>
                                        <option value="87 - Nota de Crédito Especial">87 - Nota de Crédito Especial</option>
                                        <option value="88 - Nota de Débito Especial">88 - Nota de Débito Especial</option>
                                        <option value="91 - Comprobante de No Domiciliado">91 - Comprobante de No Domiciliado</option>
                                        <option value="96 - Exceso de crédito fiscal por retiro de bienes">96 - Exceso de crédito fiscal por retiro de bienes</option>
                                        <option value="97 - Nota de Crédito - No Domiciliado">97 - Nota de Crédito - No Domiciliado</option>
                                        <option value="98 - Nota de Débito - No Domiciliado">98 - Nota de Débito - No Domiciliado</option>
                                        <option value="99 - Otros - Consolidado de Boletas de Venta">99 - Otros - Consolidado de Boletas de Venta</option>
                                    </select>
                                </div>

                                <div class="md:col-span-8">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Glosa / Detalle</label>
                                    <textarea name="glosa" rows="1" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm placeholder-slate-300 transition-all uppercase" placeholder="Ej: Compra de materiales..."></textarea>
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Responsable</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        </span>
                                        <input type="text" name="responsable" value="{{ Auth::user()->name }}" class="w-full pl-9 rounded-lg border-slate-300 bg-white text-slate-700 text-sm focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all uppercase">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-slate-700 font-bold text-sm uppercase tracking-wide border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                Datos del Proveedor
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">RUC *</label>
                                    <input type="text" name="ruc" id="ruc" x-model="ruc" @input="buscar()" list="lista_rucs" autocomplete="off" maxlength="11" required class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm font-mono" placeholder="20XXXXXXXXX">
                                    
                                    <span x-show="buscando" class="text-[10px] text-emerald-600 font-bold mt-1 block tracking-tight" style="display: none;">Buscando...</span>
                                    <span x-show="mensajeExito" class="text-[10px] text-emerald-600 font-bold mt-1 block tracking-tight" style="display: none;">¡Proveedor autocompletado!</span>
                                </div>

                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Razón Social *</label>
                                    <input type="text" name="razon_social" id="razon_social" x-model="razon_social" list="lista_razones" autocomplete="off" required class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm transition-colors duration-300 uppercase" placeholder="Nombre de la empresa...">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Serie - Número</label>
                                    <input type="text" name="n_comprobante" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm uppercase" placeholder="F001-00001">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">N° Guía</label>
                                    <input type="text" name="guia" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Estado Guía</label>
                                    <input type="text" name="estado_guia" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                                <div class="md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ref. NC/ND</label>
                                    <input type="text" name="nc_nd" class="w-full rounded-lg border-slate-300 text-sm shadow-sm uppercase">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 bg-emerald-50/50 p-6 rounded-xl border border-emerald-100">
                            <h4 class="text-emerald-800 font-bold text-sm uppercase tracking-wide border-b border-emerald-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-emerald-200 text-emerald-800 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                Importes y Moneda
                            </h4>

                            <div class="flex flex-wrap gap-8 mb-6 items-end">
                                <label class="inline-flex items-center cursor-pointer group mb-2">
                                    <input type="checkbox" id="checkNoGravado" name="check_no_gravado" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500 w-5 h-5 transition-all">
                                    <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-emerald-700 transition">Operación No Gravada (Sin IGV)</span>
                                </label>

                                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" id="checkTC" name="check_tc" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500 w-5 h-5 transition-all">
                                        <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-emerald-700 transition">Pago en Dólares (USD)</span>
                                    </label>
                                    
                                    <div id="divTasaCambio" class="hidden flex items-center gap-2 border-l border-slate-200 pl-3 ml-2 transition-all animate-fade-in-left">
                                        <span class="text-xs font-bold text-slate-500">T.C.:</span>
                                        <input type="number" step="0.001" name="tasa_cambio" id="inputTasaCambio" value="3.750" 
                                               class="w-20 py-1 px-2 text-sm font-bold text-emerald-700 border border-emerald-300 rounded focus:ring-emerald-500 focus:border-emerald-500 shadow-inner"
                                               title="Ingrese el Tipo de Cambio del día">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                                <div class="bg-white p-3 rounded-lg border border-emerald-200 shadow-sm">
                                    <label class="block text-emerald-800 font-extrabold text-xs mb-1">MONTO INGRESADO</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-emerald-600 font-bold" id="simboloMoneda">S/</span>
                                        <input type="number" step="0.01" name="monto_ingresado" id="inputMonto" required 
                                               class="w-full pl-8 border-emerald-300 ring-2 ring-emerald-50 rounded-md text-xl font-bold text-right text-emerald-900 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                                               placeholder="0.00">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1 text-right">Base Imponible</label>
                                    <input type="text" name="base_imponible" id="inputBase" readonly class="w-full bg-slate-100 border-slate-200 rounded-lg text-sm text-right font-mono text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1 text-right">IGV (18%)</label>
                                    <input type="text" name="igv" id="inputIGV" readonly class="w-full bg-slate-100 border-slate-200 rounded-lg text-sm text-right font-mono text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1 text-right">TOTAL FINAL (S/)</label>
                                    <input type="text" name="total_final" id="inputTotalFinal" readonly class="w-full bg-slate-800 border-slate-800 rounded-lg text-lg text-right font-bold text-white shadow-md">
                                    <input type="hidden" name="monto_no_gravado" id="hiddenNoGravado" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 border border-yellow-200 rounded-xl p-6 bg-yellow-50/30">
                            <div class="flex items-center gap-3 mb-4 border-b border-yellow-200 pb-2">
                                <input type="checkbox" id="checkDetraccion" class="rounded border-yellow-400 text-yellow-600 shadow-sm w-5 h-5 focus:ring-yellow-500 transition-all">
                                <h4 class="text-yellow-800 font-bold text-sm uppercase tracking-wide">Aplicar Detracción (SPOT)</h4>
                            </div>
                            <div id="panelDetraccion" class="grid grid-cols-1 md:grid-cols-3 gap-6 opacity-50 pointer-events-none transition-opacity duration-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipo de Servicio (%)</label>
                                    <select id="selectPorcentajeDetraccion" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-all">
                                        <option value="0.015">1.5% - Insumos Agrícolas</option>
                                        <option value="0.04">4.00% - Transporte / Mov. Tierra</option>
                                        <option value="0.09">9.00% - Otros Bienes</option>
                                        <option value="0.10">10.00% - Alquileres / Arena</option>
                                        <option value="0.12">12.00% - Mantenimiento</option>
                                        <option value="0.15">15.00% - Residuos / Chatarra</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Monto Detracción (S/)</label>
                                    <input type="text" name="detraccion_monto" id="inputMontoDetraccion" readonly class="w-full bg-white border-slate-300 rounded-lg text-sm text-right font-bold text-red-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Fecha Pago Detr.</label>
                                    <input type="date" name="detraccion_fecha" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-slate-700 font-bold text-sm uppercase tracking-wide border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                Tesorería y Pagos
                            </h4>
                            
                            <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-lg mb-6">
                                <label class="block text-sm font-bold text-blue-800 mb-3 uppercase tracking-wide">¿Cómo se realizó el pago?</label>
                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center cursor-pointer hover:bg-white p-2 rounded-lg transition shadow-sm border border-transparent hover:border-slate-200">
                                        <input type="radio" name="metodo_pago" value="Efectivo" class="form-radio text-green-600 h-5 w-5 focus:ring-green-500" required>
                                        <span class="ml-2 font-bold text-slate-700 flex items-center gap-1">
                                            💵 Efectivo
                                        </span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer hover:bg-white p-2 rounded-lg transition shadow-sm border border-transparent hover:border-slate-200">
                                        <input type="radio" name="metodo_pago" value="Digital" class="form-radio text-blue-600 h-5 w-5 focus:ring-blue-500" required>
                                        <span class="ml-2 font-bold text-slate-700 flex items-center gap-1">
                                            📱 Digital (Transf. / Yape / Plin)
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Estado del Pago</label>
                                    <select name="estado_pago" id="selectEstado" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm font-medium transition-all">
                                        <option value="PENDIENTE">🔴 PENDIENTE (Por Pagar)</option>
                                        <option value="CANCELADO">🟢 CANCELADO (Pagado)</option>
                                        <option value="ANULADO">⚫ ANULADO</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Saldo Pendiente (S/)</label>
                                    <input type="number" step="0.01" name="saldo_pendiente" id="inputSaldo" class="w-full rounded-lg border-slate-300 bg-white text-sm text-right font-extrabold text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 border-t border-slate-100">
                            <a href="{{ route('egresos.index') }}" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg shadow-sm hover:bg-slate-50 transition">Cancelar</a>
                            <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-lg shadow-lg hover:bg-emerald-700 hover:shadow-emerald-500/30 transition transform hover:-translate-y-0.5">💾 Guardar Operación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // === 1. LÓGICA DEL BUSCADOR DE RUC EN LA BASE DE DATOS EGRESOS (ALPINE JS) ===
        document.addEventListener('alpine:init', () => {
            Alpine.data('buscadorProveedores', () => ({
                ruc: '',
                razon_social: '',
                buscando: false,
                mensajeExito: false,

                async buscar() {
                    // Solo busca si es un RUC completo o si es seleccionado de la lista
                    if (this.ruc.length === 11 || this.ruc.length === 8) {
                        this.buscando = true;
                        this.mensajeExito = false;

                        try {
                            let respuesta = await fetch(`/egresos/buscar-ruc?ruc=${this.ruc}`);
                            let datos = await respuesta.json();

                            if (datos.encontrado) {
                                this.razon_social = datos.razon_social;
                                this.mensajeExito = true;
                            }
                        } catch (error) {
                            console.error("Error al buscar el RUC:", error);
                        } finally {
                            this.buscando = false;
                        }
                    } else {
                        this.mensajeExito = false;
                    }
                }
            }));
        });

        // Lógica bidireccional extra para que si eligen la razón social de la lista, se llene el RUC
        document.addEventListener('DOMContentLoaded', function() {
            const rucInput = document.getElementById('ruc');
            const razonSocialInput = document.getElementById('razon_social');
            
            if(razonSocialInput && rucInput) {
                razonSocialInput.addEventListener('change', function() {
                    let opciones = document.getElementById('lista_razones').options;
                    for (let i = 0; i < opciones.length; i++) {
                        if (opciones[i].value === this.value) {
                            // En el value de la lista_razones guardamos el RUC en el texto interno
                            rucInput.value = opciones[i].text;
                            // Disparamos evento para que Alpine actualice su estado si es necesario
                            rucInput.dispatchEvent(new Event('input'));
                            break;
                        }
                    }
                });
            }
        });

        // === 2. LÓGICA DE LA CALCULADORA DE MONTOS (VANILLA JS ORIGINAL) ===
        document.addEventListener('DOMContentLoaded', function() {
            const checkNoGravado = document.getElementById('checkNoGravado');
            const checkTC = document.getElementById('checkTC');
            const divTasaCambio = document.getElementById('divTasaCambio');
            const inputTasaCambio = document.getElementById('inputTasaCambio');
            const inputMonto = document.getElementById('inputMonto');
            const simboloMoneda = document.getElementById('simboloMoneda');
            const inputBase = document.getElementById('inputBase');
            const inputIGV = document.getElementById('inputIGV');
            const inputTotalFinal = document.getElementById('inputTotalFinal');
            const hiddenNoGravado = document.getElementById('hiddenNoGravado');
            const checkDetraccion = document.getElementById('checkDetraccion');
            const panelDetraccion = document.getElementById('panelDetraccion');
            const selectPorcentaje = document.getElementById('selectPorcentajeDetraccion');
            const inputMontoDetraccion = document.getElementById('inputMontoDetraccion');
            const selectEstado = document.getElementById('selectEstado');
            const inputSaldo = document.getElementById('inputSaldo');

            function calcular() {
                let monto = parseFloat(inputMonto.value) || 0;
                let tasa = parseFloat(inputTasaCambio.value) || 3.75;

                if (checkTC.checked) {
                    monto = monto * tasa;
                    simboloMoneda.innerText = "$";
                    simboloMoneda.classList.add('text-orange-500');
                    divTasaCambio.classList.remove('hidden');
                } else {
                    simboloMoneda.innerText = "S/";
                    simboloMoneda.classList.remove('text-orange-500');
                    divTasaCambio.classList.add('hidden');
                }

                let base = 0;
                let igv = 0;
                let total = monto;

                if (checkNoGravado.checked) {
                    base = 0;
                    igv = 0;
                    inputBase.value = "0.00";
                    inputIGV.value = "0.00";
                    inputBase.classList.add('text-slate-300');
                    inputIGV.classList.add('text-slate-300');
                    hiddenNoGravado.value = total.toFixed(2);
                } else {
                    base = total / 1.18;
                    igv = total - base;
                    inputBase.classList.remove('text-slate-300');
                    inputIGV.classList.remove('text-slate-300');
                    inputBase.value = base.toFixed(2);
                    inputIGV.value = igv.toFixed(2);
                    hiddenNoGravado.value = "0";
                }

                inputTotalFinal.value = total.toFixed(2);

                if (checkDetraccion.checked) {
                    let porcentaje = parseFloat(selectPorcentaje.value);
                    let detraccion = total * porcentaje;
                    inputMontoDetraccion.value = detraccion.toFixed(2);
                }

                if (selectEstado.value === 'PENDIENTE') {
                    inputSaldo.value = total.toFixed(2);
                }
            }

            inputMonto.addEventListener('input', calcular);
            inputTasaCambio.addEventListener('input', calcular);
            checkTC.addEventListener('change', calcular);
            checkNoGravado.addEventListener('change', calcular);
            
            checkDetraccion.addEventListener('change', function() {
                if (this.checked) {
                    panelDetraccion.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    panelDetraccion.classList.add('opacity-50', 'pointer-events-none');
                    inputMontoDetraccion.value = '';
                }
                calcular();
            });

            selectPorcentaje.addEventListener('change', calcular);

            selectEstado.addEventListener('change', function() {
                if (this.value === 'CANCELADO' || this.value === 'ANULADO') {
                    inputSaldo.value = '0.00';
                    inputSaldo.readOnly = true;
                    inputSaldo.classList.add('bg-slate-100', 'text-slate-400');
                    inputSaldo.classList.remove('bg-white', 'text-red-600');
                } else {
                    inputSaldo.readOnly = false;
                    inputSaldo.classList.remove('bg-slate-100', 'text-slate-400');
                    inputSaldo.classList.add('bg-white', 'text-red-600');
                    let totalActual = parseFloat(inputTotalFinal.value) || 0;
                    inputSaldo.value = totalActual.toFixed(2);
                }
            });
        });
    </script>
</x-app-layout>