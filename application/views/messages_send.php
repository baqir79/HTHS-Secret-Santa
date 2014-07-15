<div class="container">
    <div style="text-align: center;">
        <h3>Sending a message to pair in group <?=$code?></h3>
        <form action="" method="post">
            <input type="hidden" name="code" value="<?=$code?>">
            <label>
                Message<br/>
                <textarea name="message" required style="width: 500px; height: 300px;"></textarea>
            </label>
            <br/>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>