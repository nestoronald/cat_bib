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
        {else}
            <div class="span6"> <img src="./img/login.jpg" alt="login-igp"></div>
        {/if}
        <div class="span6">
            <div>{$msj_new_user_confirm}</br></div>
            <h3>Acceso</h3>
            <div id="msj_login">{$msj}</div>
            <form action="process_login.php" method="post" name="login_form" class="form-horizontal" name="frmReserva">
             <label for="usuario">Usuario</label>
             <input type="text" id="user" name="user">
             <label for="clave">Clave</label>
             <input type="password" id="password" name="password">
             <div class="actionbtn">
                 <!-- <button type="button" class="btn">Ingresar</button> -->
                 <input type="submit" class="btn" value="Ingresar">
                 <span><a href="register.php">Registrarse</a></span>
             </div>
            </form>

             <!-- <form class="form-inline" onsubmit="xajax_verificaUsuarioShow(xajax.getFormValues(formLogin)); return false;" id="formLogin" method="post">
                    <div class="input-prepend">
                    <span class="add-on label-id" ></span>
                    <input class="input-small" id="usuario" name="usuario" type="text" placeholder="Usuario">
                    </div>
                    <div class="input-prepend">
                    <span class="add-on label-pw"></span>
                    <input class="input-small" id="clave" name="clave" type="password" placeholder="Contraseña">
                    </div>
                    <input type="submit" name="Login" class="btn" value="Ingresar">
                    <div id="error"></div>
            </form> -->
        </div>
    </div>

{/block}
