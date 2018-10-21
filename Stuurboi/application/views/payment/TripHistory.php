<div class="tableHeader col-lg-9">
    <!--  TRIPS VIEW -->
    <h3 class="text-center"> YOUR TRIPS </h3>     
    <div class="row" style="background-color: #ffffff;">
        <table class="table table-dark">
            <thead>
                <tr> 
                    <th scope="col">Date</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Mileage</th>
                    <th scope="col">Fare</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <?php
            if (count($trips) > 0):
                $totalFare = 0;
                foreach ($trips as $trip):
                    $totalFare += $trip->fare;
                    ?>
                    <tbody>
                        <tr >
                            <td><?php echo date('Y-m-d', strtotime($trip->dateCreated)); ?></td>
                            <td><?php echo $trip->duration; ?></td>
                            <td><?php echo $trip->mileage; ?></td>
                            <td><?php echo $trip->fare; ?></td>                                                                                                                      
                            <td><?php echo $trip->status; ?></td>
                            <td><input type="submit" value="Confirm payment" class="btn btn-default btn-block"/></td>
                        </tr>

                    </tbody>            
                <?php endforeach; ?>
                <tbody>
                    <tr >
                        <td><b>Total </b></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $totalFare; ?></td>
                        <td></td>
                    </tr>
                </tbody>

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


