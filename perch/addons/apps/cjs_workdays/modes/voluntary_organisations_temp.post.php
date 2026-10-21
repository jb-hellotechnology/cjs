<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Voluntary Organisations - Do You Want To Import These Records?'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/voluntary_organisations/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  #Main Panel Start

  $HTML->main_panel_start();

  echo $Form->form_start();

?>
    <table class="d">
        <thead>
            <tr>                
                <th>VOrg Name</th>
                <th>Advert Email</th>
                <th>Advert Website</th>
                <th>Advert Website Short</th>
                <th>Advert Display</th>
                <th>Logo</th>
                <th>Logo ALT</th>
                <th>Display Advert</th>
                <th>Banner Ad</th>
                <th>Banner Ad Pos</th>
                <th>Region</th>
                <th>County</th>
                <th>Vol Place</th>
                <th>Vol Opps</th>
                <th>Work Days</th>
                <th>Surveys</th>
                <th>URLAdverts</th>
                <th>Organisation ID</th>
                <th>Organisation Name</th>
                <th>Contact Name</th>
                <th>Contact Job Title</th>
                <th>Contact Telephone</th>
                <th>Contact Email</th>
                <th>Contact Notes</th>
                <th>First Online</th>
                <th>Check Date</th>
                <th>Date Updated</th>
                <th>Display Until</th>
                <th>Advert Type</th>
                <th>Advert Paid</th>
                <th>Advert Cost</th>
                <th>Free Ad</th>
                <th>Advert Status</th>
                <th>vorganisationDynamicFields</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($vorganisations_wd as $VOrganisation_wd) {
?>
            <tr>
                <td><?php echo $VOrganisation_wd['vorgName']; ?></td>
                <td><?php echo $VOrganisation_wd['advertEmail']; ?></td>
                <td><?php echo $VOrganisation_wd['advertWebsite']; ?></td>
                <td><?php echo $VOrganisation_wd['advertWebsiteShort']; ?></td>
                <td><?php echo $VOrganisation_wd['advertDisplay']; ?></td>
                <td><?php echo $VOrganisation_wd['logo']; ?></td>
                <td><?php echo $VOrganisation_wd['logoAlt']; ?></td>
                <td><?php echo $VOrganisation_wd['displayAd']; ?></td>
                <td><?php echo $VOrganisation_wd['bannerAd']; ?></td>
                <td><?php echo $VOrganisation_wd['bannerAdPos']; ?></td>
                <td><?php echo $VOrganisation_wd['region']; ?></td>
                <td><?php echo $VOrganisation_wd['county']; ?></td>
                <td><?php echo $VOrganisation_wd['cVolPlace']; ?></td>
                <td><?php echo $VOrganisation_wd['cVolOpps']; ?></td>
                <td><?php echo $VOrganisation_wd['cWorkDays']; ?></td>
                <td><?php echo $VOrganisation_wd['cSurveys']; ?></td>
                <td><?php echo $VOrganisation_wd['URLAdverts']; ?></td>
                <td><?php echo $VOrganisation_wd['organisationID']; ?></td>
                <td><?php echo $VOrganisation_wd['organisationName']; ?></td>
                <td><?php echo $VOrganisation_wd['contactName']; ?></td>
                <td><?php echo $VOrganisation_wd['contactJobTitle']; ?></td>
                <td><?php echo $VOrganisation_wd['contactTelephone']; ?></td>
                <td><?php echo $VOrganisation_wd['contactEmail']; ?></td>
                <td><?php echo $VOrganisation_wd['contactNotes']; ?></td>
                <td><?php echo $VOrganisation_wd['firstOnline']; ?></td>
                <td><?php echo $VOrganisation_wd['checkDate']; ?></td>
                <td><?php echo $VOrganisation_wd['dateUpdated']; ?></td>
                <td><?php echo $VOrganisation_wd['displayUntil']; ?></td>
                <td><?php echo $VOrganisation_wd['advertType']; ?></td>
                <td><?php echo $VOrganisation_wd['advertPaid']; ?></td>
                <td><?php echo $VOrganisation_wd['advertCost']; ?></td>
                <td><?php echo $VOrganisation_wd['freeAd']; ?></td>
                <td><?php echo $VOrganisation_wd['advertStatus']; ?></td>
                <td><?php echo $VOrganisation_wd['vorganisationDynamicFields']; ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>

<?php    
    echo $Form->submit_field('btnSubmit', 'Import', '');

    echo $Form->form_end();	       

  #Main Panel End

  $HTML->main_panel_end();