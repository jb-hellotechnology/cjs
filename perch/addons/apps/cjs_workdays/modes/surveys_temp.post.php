<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Surveys'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/surveys/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  //if (PerchUtil::count($surveys)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>                
                <th>Title</th>
                <th>Region</th>
                <th>Section</th>
                <th>Provider</th>
                <th>Advert Email</th>
                <th>Advert Website</th>
                <th>Advert Website Short</th>
                <th>Advert Display</th>
                <th>Logo</th>
                <th>Logo ALT</th>
                <th>Display Ad</th>
                <th>Banner Ad</th>
                <th>Banner Ad Pos</th>
                <th>Office ID</th>
                <th>Office Name</th>
                <th>Contact Name</th>
                <th>Contact Email</th>
                <th>First Online</th>
                <th>Check Date</th>
                <th>Display Until</th>
                <th>Advert Type</th>
                <th>Advert Paid</th>
                <th>Advert Cost</th>
                <th>Free A</th>
                <th>Advert Status</th>
                <th>Date Updated</th>
                <th>surveyDynamicFields</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($surveys as $Survey) {
?>
            <tr>
                <td><?php echo $Survey['Title']; ?></td>
                <td><?php echo $Survey['region']; ?></td>
                <td><?php echo $Survey['section']; ?></td>
                <td><?php echo $Survey['provider']; ?></td>
                <td><?php echo $Survey['advertEmail']; ?></td>
                <td><?php echo $Survey['advertWebsite']; ?></td>
                <td><?php echo $Survey['advertWebsiteShort']; ?></td>
                <td><?php echo $Survey['advertDisplay']; ?></td>
                <td><?php echo $Survey['logo']; ?></td>
                <td><?php echo $Survey['logoAlt']; ?></td>
                <td><?php echo $Survey['displayAd']; ?></td>
                <td><?php echo $Survey['bannerAd']; ?></td>
                <td><?php echo $Survey['bannerAdPos']; ?></td>
                <td><?php echo $Survey['organisationID']; ?></td>
                <td><?php echo $Survey['organisationName']; ?></td>
                <td><?php echo $Survey['contactName']; ?></td>
                <td><?php echo $Survey['contactEmail']; ?></td>
                <td><?php echo $Survey['firstOnline']; ?></td>
                <td><?php echo $Survey['checkDate']; ?></td>
                <td><?php echo $Survey['displayUntil']; ?></td>
                <td><?php echo $Survey['advertType']; ?></td>
                <td><?php echo $Survey['advertPaid']; ?></td>
                <td><?php echo $Survey['advertCost']; ?></td>
                <td><?php echo $Survey['freeAd']; ?></td>
                <td><?php echo $Survey['advertStatus']; ?></td>
                <td><?php echo $Survey['dateUpdated']; ?></td>
                <td><?php echo $Survey['surveyDynamicFields']; ?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>

<?php    

    //Submit Button, date field and select field
    echo $Form->submit_field('btnSubmit', 'Import', '');

    echo $Form->form_end();	 
    //} // if things


  #Main Panel End

  $HTML->main_panel_end();