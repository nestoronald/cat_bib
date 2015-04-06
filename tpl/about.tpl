    <div class="row-fluid reservation myprofile">
        <div class="span3">
            <ul class="nav nav-sidebar nav-list">
                <li class="active"><a href="#acceso"> <i class="icon-book"></i> Datos de Acceso <i class="icon-chevron-right right"></i></a></li>
                <li class=""><a href="#datos_personal"><i class="icon-book"></i> Información de usuario <i class="icon-chevron-right right"></i></a></li>

            </ul>
        </div>
        <div class="span9">
            <div class="reser" id="acceso">
                {if $smarty.session.idusers}
                <div class="block_igp">
                    <h3>Datos de Acceso</h3>
                    <p> <span>Usuario:</span> {$smarty.session.admin}</p>
                    <p> <span>Contraseña:</span> *** <a href="#editMyPass" data-toggle="modal">Cambiar</a></p>
                    <div id="editMyPass" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-header">
                            <button type="button" class="close exit" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Cambiar Contraseña</h3>
                          </div>
                          <div class="modal-body" id="modalbody">
                            <form name="frmEditPass" id="frmEditPass">
                                <input type="hidden" name="iduser" value="{$smarty.session.idusers}">
                                <div id="msj-pass" class="error"></div>
                                <p> <span>Contraseña Actual:</span></p>
                                <input name="pass" type="password" >
                                <p> <span>Contraseña Nueva:</span> </p>
                                <input name="newpass" type="password">
                                <p> <span>Repetir Contraseña Nueva:</span></p>
                                <input name="renewpass" type="password">
                            </form>
                          </div>
                          <div class="modal-footer" id="modalfooter">
                            <button class="btn" onclick="xajax_editPassAdmin(xajax.getFormValues('frmEditPass')); return false;">Guardar</button>
                                   <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                          </div>
                    </div>
                </div>

                {/if}
            </div>
            <div class="reser hide" id="datos_personal">
                <div class="block_igp">
                    <h3>Información de usuario <span>(<a href="#editDataSede" data-toggle="modal"><i class="icon-pencil"></i></a>)</span></h3>
                    <p> <span>Biblioteca:</span> {$smarty.session.profile.biblioteca}</p>
                    <p> <span>Nombre Completo:</span> {$smarty.session.profile.names}</p>
                    <p> <span>Email:</span> <span id="email_profile"> </span></p>
                    <p> <span>DNI:</span> {$smarty.session.profile.dni}</p>
                    <p> <span>Dirección:</span> {$smarty.session.profile.address} </p>
                    <p> <span>Teléfono:</span> {$smarty.session.profile.tel} </p>
                    <div id="profileconte"></div>

                    <div id="editDataSede" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-header">
                            <button type="button" class="close exit" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Editar mi perfil</h3>
                          </div>
                          <div class="modal-body" id="modalbody_pro">
                            <div id="msj-sede" class="error"></div>
                            <form name="frmDataSede" id="frmDataSede">
                                <input type="hidden" name="users_sede" value="{$smarty.session.users_sede}">
                                <p> <span>Biblioteca: </span>
                                <input type="text" name="biblioteca" value="{$smarty.session.profile.biblioteca}"></p>
                                <p> <span>Nombre Completo:</span>
                                <input type="text" name="names" value="{$smarty.session.profile.names}"></p>
                                <p> <span>Email:</span>
                                <input type="text" name="email" value="{$smarty.session.profile.email}"></p>
                                <p> <span>DNI:</span>
                                <input type="text" name="dni" value="{$smarty.session.profile.dni}"></p>
                                <p> <span>Dirección:</span>
                                <textarea name="address" cols="10" >{$smarty.session.profile.address}</textarea></p>
                                <p> <span>Teléfono:</span>
                                <input type="text" name="tel" value="{$smarty.session.profile.tel}"></p>
                            </form>
                          </div>
                          <div class="modal-footer" id="modalfooter_pro">
                            <button class="btn" onclick="xajax_editDataSede(xajax.getFormValues('frmDataSede'))">Guardar</button>
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                          </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
