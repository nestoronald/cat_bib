{extends file="index.tpl"}
{block name=title_pag}{/block}
{block name=reserva}{/block}
{block name=content}
    {if $reserva}
        {assign var=spanconte value="col80" scope="global"}
    {else}
        {assign var=spanconte value="col50" scope="global"}
    {/if}

    <div class="login-form {$spanconte}">
        {if $reserva}
            <div class="span6"> <h3>Lista de Reservas</h3>{$reserva}</div>
        {/if}
        <div class="span6">
            <h3>Acceso</h3>
            <div id="msj_login">{$msj}</div>
            <form action="process_login.php" method="post" name="login_form" class="form-horizontal" name="frmReserva">
             <label for="usuario">Usuario</label>
             <input type="text" id="user" name="user">
             <label for="clave">Clave</label>
             <input type="password" id="password" name="password">
             <div class="actionbtn">
                 <button type="button" class="btn" onclick="formhash(this.form, this.form.password);">Ingresar</button>
                 <span><a href="register.php">Registrarse</a></span>
             </div>
            </form>
        </div>
    </div>

{/block}
