{extends file="index.tpl"}
{block name=title_pag}<h3>Mis Perfil </h3>{/block}
{block name=reserva}{/block}
{block name=content}
    <div class="row-fluid reservation myprofile">
        <div class="span3">
            <ul class="nav nav-sidebar nav-list">
                <li class="active"><a href="#acceso"> <i class="icon-book"></i> Datos de Acceso <i class="icon-chevron-right right"></i></a></li>
                <li><a href="#datos_personal"><i class="icon-book"></i> Información personal <i class="icon-chevron-right right"></i></a></li>

            </ul>
        </div>
        <div class="span9">
            <div class="reser" id="acceso">
                {if $smarty.session.user_id}
                <div class="block_igp">
                    <h3>Datos de Acceso</h3>
                    <p> <span>Usuario:</span> {$smarty.session.username}</p>
                    <p> <span>Email:</span> <span id="email_profile"> </span></p>
                    <p> <span>Contraseña:</span> *** <a href="#editMyPass" data-toggle="modal">Cambiar</a></p>
                    <div id="editMyPass" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Cambiar Contraseña</h3>
                          </div>
                          <div class="modal-body" id="modalbody">
                            <form name="frmEditPass" id="frmEditPass">
                                <input type="hidden" name="iduser" value="{$details.id}">
                                <div id="msj-pass" class="error"></div>
                                <p> <span>Contraseña Actual:</span></p>
                                <input name="pass" type="password" >
                                <p> <span>Contraseña Nueva:</span> </p>
                                <input name="newpass" type="password">
                                <p> <span>Repetir Contraseña Nueva:</span></p>
                                <input name="renewpass" type="password">
                                <!-- <div>
                                   <button class="btn" onclick="xajax_editPass(form)">Guardar</button>
                                   <button class="btn" data-dismiss="modal" aria-hidden="true">cancelar</button>
                                </div> -->
                            </form>
                          </div>
                          <div class="modal-footer" id="modalfooter">
                            <button class="btn" onclick="xajax_editPass(xajax.getFormValues('frmEditPass')); return false;">Guardar</button>
                                   <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                          </div>
                    </div>
                </div>

                    <form name="frmreserva" id="frmreserva">
                        <div class="actionbtn">
                        </div>
                    </form>

                {/if}
            </div>
            <div class="reser hide" id="datos_personal">
                <div class="block_igp">
                    <h3>Información personal <span>(<a href="#editMyProfile" onclick="xajax_editMyProfile_frm();" data-toggle="modal"><i class="icon-pencil"></i></a>)</span></h3>
                    <!-- <p> <span>Nombre Completo:</span> {$details.names}</p>
                    <p> <span>DNI:</span> {$details.dni}</p>
                    <p> <span>Dirección:</span> {$details.dir} </p>
                    <p> <span>Teléfono:</span> {$details.tel} </p> -->
                    <div id="profileconte"></div>

                    <div id="editMyProfile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Editar mi perfil</h3>
                          </div>
                          <div class="modal-body" id="modalbody_pro">
                            <div id="msj-profile" class="error"></div>
                            <form name="frmMyProfile" id="frmMyProfile">
                                <!-- <input type="hidden" name="iduser" value="{$details.id}">
                                <p> <span>Nombre Completo:</span> </p>
                                <input type="text" name="names" value="{$details.names}">
                                <p> <span>DNI:</span>  </p>
                                <input type="text" name="dni" value="{$details.dni}">
                                <p> <span>Dirección:</span>  </p>
                                <textarea name="dir" cols="10" >{$details.dir}</textarea>
                                <p> <span>Teléfono:</span> </p>
                                <input type="text" name="tel" value="{$details.tel}"> -->

                            </form>
                          </div>
                          <div class="modal-footer" id="modalfooter_pro">
                            <button class="btn" onclick="xajax_editMyprofile(xajax.getFormValues('frmMyProfile'))">Guardar</button>
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                          </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>xajax_details_member()</script>
{/block}