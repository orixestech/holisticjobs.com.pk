<div id="titlebar" class="single">
  <div class="container">
    <div class="sixteen columns">
      <h2>
        <?=$CONTENT['content_title']?>
      </h2>
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="<?=$path?>">Home</a></li>
          <li>
            <?=$CONTENT['content_title']?>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Content
================================================== -->
<!-- Container -->
<div class="container">
  <div class="sixteen columns">
    <div class="seven columns">
      <div class="my-account">
        <ul class="tabs-nav">
          <li class=""><a href="#tab1">Employer</a></li>
          <li><a href="#tab2">Employee</a></li>
        </ul>
        <div class="tabs-container">
          <!-- Login -->
          <div class="tab-content" id="tab1" style="display: none;">
            <h3 class="margin-bottom-10 margin-top-10">Employer</h3>
            <form method="post" class="login">
              <p class="form-row form-row-wide">
                <label for="username">Username or Email Address:</label>
                <input type="text" class="input-text" name="username" id="username" value="" />
              </p>
              <p class="form-row form-row-wide">
                <label for="password">Password:</label>
                <input class="input-text" type="password" name="password" id="password" />
              </p>
              <p class="form-row">
                <input type="submit" class="button" name="login" value="Login" />
            </form>
          </div>
          <!-- Register -->
          <div class="tab-content" id="tab2" style="display: none;">
            <h3 class="margin-bottom-10 margin-top-10">Employee</h3>
            <form method="post" class="login">
              <p class="form-row form-row-wide">
                <label for="username">Username or Email Address:</label>
                <input type="text" class="input-text" name="username" id="username" value="" />
              </p>
              <p class="form-row form-row-wide">
                <label for="password">Password:</label>
                <input class="input-text" type="password" name="password" id="password" />
              </p>
              <p class="form-row">
                <input type="submit" class="button" name="login" value="Login" />
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="seven columns float-right">
      <div class="my-account">
        <ul class="tabs-nav">
          <li class=""><a href="#tab1">Forget Password</a></li>
        </ul>
        <div class="tabs-container">
          <!-- Login -->
          <div class="tab-content" id="tab1" style="display: none;">
            <h3 class="margin-bottom-10 margin-top-10">Forget Password</h3>
            <form method="post" class="login">
              <p class="form-row form-row-wide">
                <label for="user_email">Email Address:</label>
                <input type="text" class="input-text" name="user_email" id="user_email" value="" />
              </p>
              <p class="form-row form-row-wide">
                <label for="type">Account Type:</label>
                <select data-placeholder="Choose Type" class="chosen-select" name="type" id="type" >
                  <option value="Employer">Employer</option>
                  <option value="Employee">Employee</option>
                </select>
              </p>
              <p class="form-row">
                <input type="submit" class="button" name="login" value="Login" />
            </form>
          </div>
          <!-- Register -->
        </div>
      </div>
    </div>
  </div>
</div>
