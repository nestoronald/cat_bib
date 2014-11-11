{extends file="index.tpl"}
{block name=title_pag}{/block}
{block name=content}
  <div class="col50 m_center">
    <h3 class="txt-azul">Registro de usuarios</h3>
    <div id="msj_register">{$msj_register}</div>
    <div class="span12">
        <div class="span6">
            <img src="./img/registro-signup.png" alt="register_biblioteca_igp">
        </div>
        <div class="span6">
            <form action="processRegister.php" method="post" name="login_form">
            <label for="usuario">Usuario</label>
            <input type="text" id="user" name="user">
            <label for="mail">Correo </label>
            <input type="text" id="mail" name="mail">
            <label for="password">Clave </label>
            <input type="password" id="password" name="password">
            <label for="newpassword">Vuelva escribir clave </label>
            <input type="password" id="repassword" name="repassword">
            <div class="actionbtn">
                <input type="submit" class="btn" value="Registrar">
                <p>¿Ya eres usuario? <a href="login.php">Ingresar</a></p>
            </div>
            </form>
        </div>
    </div>
 </div>
{/block}