{extends file="index.tpl"}
{block name=title_pag}<h3>Mis Reservas </h3>{/block}
{block name=reserva}{/block}
{block name=content}
    <div class="row-fluid reservation">
        <div class="span3">
            <ul class="nav nav-sidebar nav-list">
                <li class="active"><a href="#ultimas"> <i class="icon-book"></i> Ultima <i class="icon-chevron-right right"></i></a></li>
                <li><a href="#historial"><i class="icon-book"></i> Historial <i class="icon-chevron-right right"></i></a></li>
                <!-- <li><a href="#procesados"> <i class="icon-thumbs-up"></i> Procesados <i class="icon-chevron-right right"></i></a></li> -->
                <!-- <li><a href="#noprocesados"><i class="icon-thumbs-down"></i> No Procesados <i class="icon-chevron-right right"></i></a></li> -->
            </ul>
        </div>
        <div class="span9">
            <div class="reser" id="ultimas">
                {$reservation}
                {if $smarty.session.reserva.idbook}
                    <div id="listbook">
                    <form name="frmreserva" id="frmreserva">
                        {if $smarty.session.user_id}
                            <input type="hidden" name="username" value="{$smarty.session.user_id}">
                        {/if}
                        {if $smarty.session.usertype}
                            <input type="hidden" name="usertype" value="{$smarty.session.usertype}">
                        {/if}
                        {if $smarty.session.reserva.idbook.0}
                            <input type="hidden" name="idbook[]" value="{$smarty.session.reserva.idbook.0}">
                        {/if}
                        {if $smarty.session.reserva.idbook.1}
                            <input type="hidden" name="idbook[]" value="{$smarty.session.reserva.idbook.1}">
                        {/if}
                        {if $smarty.session.reserva}
                            <input type="hidden" name="fx_register" value="{'Y-n-j'|date}">
                            <input type="hidden" name="state" value="1">

                            <label for="fx_reserve"> Fecha de Prestamo:</label>
                            <input class="span4 calendar" id="fx_reserve" type="text" name="fx_reserve" value="">

                        {/if}
                        <div class="actionbtn">
                            <input class="btn" type="button" onclick="xajax_confirmReserva(xajax.getFormValues(frmreserva)); return false" value="Reservar">
                        </div>
                    </form>
                    </div>
                {/if}
            </div>
            <div class="reser hide" id="historial">{$listReservation}</div>
            <!-- <div class="reser hide" id="procesados"></div> -->
            <!-- <div class="reser hide" id="noprocesados">noprocesados</div> -->
        </div>
    </div>
{/block}