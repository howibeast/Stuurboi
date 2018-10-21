<script src="<?php echo base_url("/node_modules/socket.io-client/dist/socket.io.js"); ?>"></script>
<script type="text/javascript">
    var socket = io('127.0.0.1:3000');
    /***********************************accept request***********************/
    $(document).ready(function () {
        //change div
        $("#accept").click(function () {
            var requestId = $(this).attr('requestId');
            jQuery.ajax({
                type: "POST",
                url: "http://localhost/team39/Stuurboi/requests/acceptRequest",
                dataType: 'json',
                data: {requestId: requestId},
                success: function (res) {
                    if (res.status = true) {
                        socket.emit('accepted request', res.tripId);
                    }
                    alert(res.message);
                }
            });
        });
    });
</script>
<div class="tableHeader col-lg-9">
    <!--  TRIPS VIEW -->
    <h3 class="text-center">New Requests</h3>     
    <div class="row" style="background-color: #ffffff;">
        <table class="table table-dark">
            <thead>
                <tr> 
                    <th scope="col">from</th>
                    <th scope="col">to</th>
                    <th scope="col">Recievers Name</th>
                    <th scope="col">Recievers Cell</th>
                </tr>
            </thead>
            <?php
            if (count($requests) > 0):
                foreach ($requests as $request):
                    ?>
                    <tbody>
                        <tr id="<?php echo $request->id; ?>">
                            <td><?php echo $request->fromAddress; ?></td>
                            <td><?php echo $request->toAddress; ?></td>
                            <td><?php echo $request->receiverName; ?></td>
                            <td><?php echo $request->receiverCell; ?></td>
                            <td>
                                <a href="#" class="btn btn-success" id="accept" requestId="<?php echo $request->id; ?>" >accept</a>
                            </td>
                            <td>
                                <input type="submit" class="btn btn-primary" value="Reject"/>
                            </td>
                        </tr>

                    </tbody>            
                <?php endforeach; ?>
            <?php else: ?>
                <tbody>
                    <tr >
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            <?php endif; ?>

        </table>
    </div>
    <!--     -->  
    <div class="row">

    </div>
    <!-- /.row -->

</div>
<!-- /.col-lg-9 -->


