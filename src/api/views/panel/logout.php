<?php if ((new Session)->get('loggedUser') == false): ?>
    <div class="container">
        <h1 class="msg msg-danger font-yekan">شما با موفقیت خارج شدید. </h1>
        <button class="button font-yekan" onclick="window.location = '<?php echo URL; ?>'">برای ادامه اینجا را کلیک
            کنید
        </button>
    </div>
<?php endif; ?>