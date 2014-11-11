{extends file="index.tpl"}
{block name=title_pag}{/block}
{block name=content}
  <div class="col30 m_center">
    <h3>Registro de usuarios</h3>
    <div id="msj_register">{$msj_register}</div>
    <form action="processChangePass.php" method="post" name="login_form">
        <label for="usuario">Usuario</label>
        <input type="text" id="user" name="user">
        <label for="mail">Correo </label>
        <input type="text" id="mail" name="mail">
        <label for="password">Clave </label>
        <input type="password" id="password" name="password">
        <label for="newpassword">Vuelva escribir Clave </label>
        <input type="password" id="newpassword" name="newpassword">
        <!-- <label for="newpassword">Vuelva escribir Clave </label>
        <input type="password" id="newpassword" name="newpassword"> -->
        <div class="actionbtn">
            <button type="button" class="btn" onclick="formhash(this.form, this.form.password,this.form.newpassword);">Registrar</button>
        </div>
    </form>
 </div>
{/block}