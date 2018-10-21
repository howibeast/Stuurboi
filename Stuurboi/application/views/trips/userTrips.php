<div class="tableHeader col-lg-9">
    <!--  TRIPS VIEW -->
    <h3 >Trip History</h3>     
    <div class="row" style="background-color: #ffffff;">
        <table class="table table-dark">
            <thead>
                <tr> 
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col">Driver</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Price</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($trips) > 0):
                    foreach ($trips as $trip) :
                        ?>
                        <tr >
                            <td><?php echo $trip->fromAddress; ?></td>
                            <td><?php echo $trip->toAddress; ?></td>
                            <td><?php echo $trip->name . ' ' . $trip->surname; ?> </td>
                            <td><?php echo $trip->duration; ?></td>   
                            <td><?php echo $trip->fare; ?> </td>
                            <td><?php echo $trip->status; ?> </td>
                            <td><?php echo $trip->dateCreated; ?> </td>         
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr >
                        <td>-</td>
                        <td>-</td>
                        <td>- </td>
                        <td>- </td>
                        <td>-</td>
                        <td>-</td>         
                        <td>-</td>
                    </tr>
<?php endif; ?>

            </tbody>

        </table>
    </div>
    <!--     -->  
    <div class="row">

    </div>
    <!-- /.row -->

</div>
<!-- /.col-lg-9 -->


