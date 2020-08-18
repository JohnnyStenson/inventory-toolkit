if(isset($_SESSION['fulfillment']) && $_SESSION['fulfillment']){
            if($inv->loi_quant <= $sObject->fields->Restock_Point__c){
                $filter_fulfillment = 1;
            }else{
                $filter_fulfillment = 0;
            }
            
        }else{
            $filter_fulfillment = 1;
        }

        if($filter_fulfillment){
            /* Combine Inv in all Locations */
            //if($aggrInvId !== )


            echo "<div class='inv_record'>"; // BEGIN div.inv_record

            echo "<div class='inv_name'>" . $$sf->fields->Name . "</div>";
            if(!empty($$sf->fields->TrackIT__Description__c)){
                echo "<div class='inv_name'>" . $$sf->fields->TrackIT__Description__c . "</div>";
            }

            echo "<div class='inv_image_container'><img class='inv_image' src='" . $$sf->fields->Image_for_ListView__c . "' /></div>";

            // Temporary Location
            if('all' != $location && 'true' == $sObject->fields->Temporary_Location__c
            ){
            ?>
                <a class='btn_red_outline<?php echo ('MNGR' == $_SESSION['role']) ? " btn_keepinvlocation' href='#'"  : "'";  ?> data-id='<?php echo $$sf->Id; ?>' data-loi='<?php echo $sObject->Id; ?>'>Temporary <?php echo ('MNGR' == $_SESSION['role']) ? ' <br />(Click to Keep or<br /> Unassign Below)' : '';  ?></a>
            <?php 
            }

            // Quantities
            echo "<div class='inv_quantity'><table class='table_quants'>";
            if('all' != $location){
                echo "<tr><td>Unit: </td><td>" . $$sf->fields->Indiv_Unit_of_Measurement_Description__c . "</td></tr>";
                echo "<tr style='border:1px solid black;'><td>Current Quantity: </td><td>" . $sObject->fields->TrackIT__Quantity__c . "</td></tr>";
                echo "<tr><td>Restock Point: </td><td>" . $sObject->fields->Restock_Point__c . "</td></tr>";
                echo "<tr><td>Optimal Quantity: </td><td>" . $sObject->fields->Optimal_Quantity__c . "</td></tr>";
                echo "<tr><td>Max Quantity: </td><td>" . $sObject->fields->Max_Storage_Capacity__c . "</td></tr>";
            }else{
                echo "<tr><td>Total Quantity:</td><td>" . $sObject->fields->TrackIT__Total_Quantity__c . "</td></tr>";
                echo "<tr><td>Unit:</td><td>" . $sObject->fields->Indiv_Unit_of_Measurement_Description__c . "</td></tr>";
            }
            echo "</table></div>";

            if('all' != $location){
                echo "<div class='inv_use hide_drawer hide_changequants' data-id='" . $$sf->Id . "'>
                        <div class='used' data-id='" . $$sf->Id . "'>
                            <input type='text' class='consumeQuant' data-id='" . $$sf->Id . "' placeholder='# USED'/>
                            
                            <label class='lbl_consumeJob' style='display:none;' data-id='" . $$sf->Id . "' >Select Job Used on to <br />Deduct from Inventory:</label>
                            <select class='consumeJob' data-id='" . $$sf->Id . "' data-location='" . $location . "' data-current='" . $sObject->fields->TrackIT__Quantity__c . "'  ><option value='0'>Choose:</option>" .
                            $ploJobs .
                            "</select>
                        </div>
                    </div>";
            }

            


            //echo "<div class='inv_barcode'><img src='".get_inventory_barcode($record['Name'])."' /></div>";
            //echo "<a class='inv_button' href='https://thundernj.lightning.force.com/lightning/r/TrackIT__Inventory__c/".$record['Id']."/view?iospref=web'>Web</a>";

            if('CREW' != $_SESSION['role']){ // BEGIN drawer
            ?>
            <div class='openDrawerBtns hide_drawer' data-id='<?php echo $$sf->Id; ?>'>
                <a href='#' class='openAdminDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>&vellip;</a>
                <!-- a href='#' class='openMoreDrawer btnOpenDrawer' data-id='<?php echo $$sf->Id; ?>'>?</!-->
            </div>
            <div class='admin_drawer' data-id='<?php echo $$sf->Id; ?>'>
                
                <!-- BEGIN Change description -->
                <input type='text' 
                    class='changeDescription hide_assign2location hide_changequants hide_moveinv' 
                    data-id='<?php echo $$sf->Id; ?>' 
                    placeholder='Change Description'
                />
                <a href='#' 
                    class='btn_changeDescription'
                    style='display:none;' 
                    data-id='<?php echo $$sf->Id; ?>'
                    data-location='<?php echo $location; ?>' >
                    Change Description
                </a>
                <!-- END Change description -->
                <?php
                if('all' != $location){ // BEGIN allow quant change
                ?>
                <a href='#' 
                    class='btn_displaychangequants blue_button hide_changequants hide_changeDescription hide_moveinv'
                    data-id='<?php echo $$sf->Id; ?>' 
                    data-location='<?php echo $location; ?>'>
                    Change Quantities
                </a>
                <div class='frm_changequants' style='display:none; border-bottom:1px solid black;' data-id='<?php echo $$sf->Id; ?>'>
                    <label>Current Quantity:</label>
                    <input type='text' 
                        class='quant_changequants input_text' 
                        data-id='<?php echo $$sf->Id; ?>' 
                        value='<?php echo $sObject->fields->TrackIT__Quantity__c; ?>'
                    />
                    <label>Restock Point::</label>
                    <input type='text' 
                        class='restock_changequants input_text' 
                        data-id='<?php echo $$sf->Id; ?>' 
                        value='<?php echo $sObject->fields->Restock_Point__c; ?>'
                    />
                    <label>Optimal Quantity:</label>
                    <input type='text' 
                        class='optimal_changequants input_text' 
                        data-id='<?php echo $$sf->Id; ?>' 
                        value='<?php echo $sObject->fields->Optimal_Quantity__c; ?>'
                    />
                    <label>Max Quantity:</label>
                    <input type='text' 
                        class='max_changequants input_text' 
                        data-id='<?php echo $$sf->Id; ?>'  
                        value='<?php echo $sObject->fields->Max_Storage_Capacity__c; ?>'
                    />
                    <a href='#' 
                    class='btn_changequants blue_button'
                    data-id='<?php echo $$sf->Id; ?>' 
                    data-location='<?php echo $location; ?>'>
                    Save Quantities
                </a>
                </div>
                <?php
                } // END allow quant change

                if('all' != $location){ // BEGIN move inventory
                ?>
                    <!-- a href='#' 
                        class='btn_moveinv blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                        data-id='<?php //echo $$sf->Id; ?>' >
                        Move Inventory Here
                    </!-->
                    <div class='frm_moveinv' data-id='<?php echo $$sf->Id; ?>' style='display:none; border-bottom:1px solid black;'>
                        
                        <input type='text' 
                            class='quant_moveinv input_text' 
                            data-id='<?php echo $$sf->Id; ?>'
                            data-loid='<?php echo $sObject->Id; ?>'
                            data-curr='<?php echo $sObject->fields->TrackIT__Quantity__c; ?>'
                            placeholder='Quantity'
                        />
    
                        <select class="select_moveinv input_select" data-id="<?php echo $$sf->Id; ?>">
                        </select>
                    </div>
                <?php
                } // END move inv


                

                if('all' == $location){ // BEGIN assign to a location
                ?>
                
                <a href='#' 
                    class='btn_assign2newlocation blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                    data-id='<?php echo $$sf->Id; ?>' >
                    Assign to a New Location
                </a>
                <div class='frm_assign2location' data-id='<?php echo $$sf->Id; ?>' style='display:none; border-bottom:1px solid black;'>
                    
                    <input type='text' 
                        class='assign_quant input_text' 
                        data-id='<?php echo $$sf->Id; ?>' placeholder='Quantity'
                    />
                    <input type='text' 
                        class='assign_restock input_text' 
                        data-id='<?php echo $$sf->Id; ?>' placeholder='Restock Point'
                    />
                    <input type='text' 
                        class='assign_optimal input_text' 
                        data-id='<?php echo $$sf->Id; ?>' placeholder='Optimal Quantity'
                    />
                    <input type='text' 
                        class='assign_max input_text' 
                        data-id='<?php echo $$sf->Id; ?>' placeholder='Max Capacity'
                    />

                    <select class="select_assign2location input_select" data-id="<?php echo $$sf->Id; ?>">
                    </select>
                </div>



                <a href='#' 
                    class='btn_movetemplocation blue_button hide_assign2location hide_changeDescription hide_moveinv' 
                    data-id='<?php echo $$sf->Id; ?>' >
                    Move to a Location Temporarily
                </a>
                <div class='frm_movetemplocation' data-id='<?php echo $$sf->Id; ?>' style='display:none; border-bottom:1px solid black;'>
                    
                    <input type='text' 
                        class='assign_movetemplocation input_text' 
                        data-id='<?php echo $$sf->Id; ?>' placeholder='Quantity'
                    />

                    <select class="select_movetemplocation input_select" data-id="<?php echo $$sf->Id; ?>">
                    </select>
                </div>
                

                <?php
                }else{
                ?>
                <a href='#' 
                    class='btn_unassignLocation blue_button hide_changequants hide_changeDescription hide_moveinv' 
                    data-loi='<?php echo $sObject->Id; ?>' 
                    data-id="<?php echo $$sf->Id; ?>">
                    Unassign This Location
                </a>
                <?php 
                } // END assign to a location
                ?>
                <a class='btn_OpenSFApp' href='salesforce1://sObject/<?php echo $$sf->Id; ?>/view'>Open in Salesforce App</a>

                <!-- BEGIN Replace Photo -->
                <div class='hide_changequants hide_changeDescription hide_assign2location hide_moveinv' data-id="<?php echo $$sf->Id; ?>">
                    <form method="post" enctype="multipart/form-data" name="formUploadFile" class='frmReplacePicture ' action="upload.php">
                        
                        <label for="replPic_<?php echo $$sf->Id; ?>" class='lbl_replacePicture'>        
                            <img src='uploads/rotate-camera-icon.png' style="display:block; margin:0 auto;"/> 
                            Replace Picture
                            <input type="file" id = "replPic_<?php echo $$sf->Id; ?>" class='replacePicture' name="file" style="display:none;" data-id='<?php echo $$sf->Id; ?>' onchange="$('#loading_overlay').css('display','block'); this.form.submit();" >
                        </label>
                        <input type='hidden' name='auth' value='legit' />
                        <input type='hidden' name='id' value='<?php echo $$sf->Id; ?>' />
                    </form>
                </div>
                <!-- END Replace Photo -->

            </div> <!-- END .admin_drawer -->
            
            <?php
            } // END drawer
            echo "</div>"; // END div.inv_record
        }