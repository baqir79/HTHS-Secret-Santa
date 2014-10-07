<div class="container">
    <div style="text-align: center;">
        <h3>Sending a message to pair in group</h3>
        <form method="post" action="<?=base_url('messages/send')?>">
            <select multiple required class="form-control" name="code">
                <?foreach($recipients as $recipient) {
                    echo '<option value="'.$recipient["code"].'">'.$recipient["name"].'</option>' ;
                }
?>
            </select>
            <label>
                Message<br/>
                <textarea name="message" required style="width: 500px; height: 300px;"></textarea>
            </label>
            <br/>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>