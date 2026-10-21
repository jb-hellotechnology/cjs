<?php

	$Emails = new CJS_Daily_Emails($API);
    $Templates = new CJS_Daily_Emails_Templates($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_daily/email.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $emailID = (int) $_GET['id'];    
        $Email = $Emails->find($emailID, true);
        $details = $Email->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Email';
      
        $buttonText = 'Update Email';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Email = false;
        $emailID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Email';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Email';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('emailID', 'subject', 'scheduledDate_day', 'scheduledDate_month', 'scheduledDate_year', 'scheduledDate_hour', 'scheduledDate_minute', 'status', 'campaignID');	    
    	$data = $Form->receive($postvars);
        
        $dt = DateTime::createFromFormat(
            'Y-n-j G:i',
            sprintf(
                '%d-%d-%d %d:%02d',
                (int) $data['scheduledDate_year'],
                (int) $data['scheduledDate_month'],
                (int) $data['scheduledDate_day'],
                (int) $data['scheduledDate_hour'],
                (int) $data['scheduledDate_minute']
            ),
            new DateTimeZone('Europe/London')
        );
        
        if (!$dt) {
            $message = $HTML->failure_message('The scheduled date is not valid.');
            // bail out rather than calling Brevo
        }
        
        $dt->setTime((int) $dt->format('G'), (int) $dt->format('i'), 0);
        
        $data['scheduledDate']  = $dt->format('Y-m-d H:i:s');   // for your DB
        $scheduledDateBrevo     = $dt->format('Y-m-d\TH:i:sP'); // for Brevo, includes offset
        
        unset($data['scheduledDate_day']);
        unset($data['scheduledDate_month']);
        unset($data['scheduledDate_year']);
        unset($data['scheduledDate_hour']);
        unset($data['scheduledDate_minute']);

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['emailDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['emailDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Emails, $Email);
        $data['emailDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        $emailContent = '';
        foreach ($dynamic_fields['_blocks'] as $block) {
            $blockType = $block['_block_type'];
        
            if ($blockType == 'textarea') {
                
                $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
        
                $emailContent .= '
                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                   <tr>
                      <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                         <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tr>
                               <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                  <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                     <tr>
                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                           <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                              <tr>
                                                 <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                       '.$text.'
                                                    </div>
                                                 </td>
                                              </tr>
                                           </table>
                                        </td>
                                     </tr>
                                  </table>
                               </th>
                            </tr>
                         </table>
                      </td>
                   </tr>
                </table>';
        
            }if ($blockType == 'jobofday') {
                
                $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                $title     = '<strong>'.$block['title'].'</strong>';
            
                $emailContent .= '
                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                   <tr>
                      <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                         <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tr>
                               <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                  <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                     <tr>
                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                           <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                              <tr>
                                                 <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                       <p><strong><span style="color: rgb(192, 80, 77);">Job of the Day:</span> '.$title.'</strong></p>
                                                       '.$text.'
                                                    </div>
                                                 </td>
                                              </tr>
                                           </table>
                                        </td>
                                     </tr>
                                  </table>
                               </th>
                            </tr>
                         </table>
                      </td>
                   </tr>
                </table>';
            
            } else if ($blockType == 'jobs') {

                $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                
                $emailContent .= '
                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                <tr>
                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tr>
                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                    <tr>
                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                            <tr>
                                                <td class="r11-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #f0f5fc; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://countryside-jobs.com/jobs" style="color:black;text-decoration:none;">Jobs</a></strong></span></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    '.$text.'
                                                    <p style="margin: 0;"><br></p>
                                                    <p style="margin: 0; text-align: center;"><a href="https://countryside-jobs.com/advertise" type="absoluteLink" target="_blank" rel="noopener noreferrer" style="color: #0867ec; text-decoration: underline;">Click to advertise your job with CJS</a></p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                </table>';
                
            } else if ($blockType == 'training') {
            
                $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                
                $emailContent .= '
                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                <tr>
                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tr>
                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                    <tr>
                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                            <tr>
                                                <td class="r11-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #d6f5e6; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/training" style="color:black;text-decoration:none;">Training Directory</a></strong></span></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    '.$text.'
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                </table>';
                
            } else if ($blockType == 'volunteers') {
            
                $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                
                $emailContent .= '
                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                <tr>
                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                            <tr>
                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                    <tr>
                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                            <tr>
                                                <td class="r11-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #fOf5fc; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/volunteers/intro" style="color:black;text-decoration:none;">Volunteers Directory</a></strong></span></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                    <div>
                                                    '.$text.'
                                                    <p style="margin: 0;"><br></p>
                                                    <p style="margin: 0; text-align: center;"><a href="https://www.countryside-jobs.com/advertise/volunteers" type="absoluteLink" target="_blank" rel="noopener noreferrer" style="color: #0867ec; text-decoration: underline;">Click here to advertise your voluntary role, with CJS it\'s free</a></p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                    </td>
                </tr>
                </table>';
                
            } else if ($blockType == 'news') {
            
                    $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                    $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                    
                    $emailContent .= '
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                       <tr>
                          <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                             <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tr>
                                   <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                      <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                         <tr>
                                            <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                               <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                  <tr>
                                                     <td class="r25-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #e8f0d6; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                        <div>
                                                           <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/news" style="color:black;text-decoration:none;">News</a></span></strong></p>
                                                        </div>
                                                     </td>
                                                  </tr>
                                                  <tr>
                                                     <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                        <div>
                                                           '.$text.'
                                                        </div>
                                                     </td>
                                                  </tr>
                                                  <tr>
                                                      <td class="r12-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                          <div>
                                                          <p>Read <a href="https://www.countryside-jobs.com/news">today\'s featured headlines here</a> and <a href="https://www.countryside-jobs.com/news/weeks-news">catch up on the week\'s news here</a>.</p>
                                                          </div>
                                                      </td>
                                                  </tr>
                                               </table>
                                            </td>
                                         </tr>
                                      </table>
                                   </th>
                                </tr>
                             </table>
                          </td>
                       </tr>
                    </table>';
                    
            } else if ($blockType == 'feature') {
            
                    $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                    $title     = '<strong>'.$block['title'].'</strong>';
                    $intro     = '<p class="default">'.$block['intro'].'</p>';
                    $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                    $link      = trim($block['link']);
                    
                    $emailContent .= '
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                    <tr>
                        <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tr>
                                <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                        <tr>
                                            <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                <tr>
                                                    <td class="r13-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #e6ffd6; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                        <div>
                                                           <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/in-depth/recent-articles" style="color:black;text-decoration:none;">Features</a></strong></span></p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                            <tr>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r19-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r20-c" align="center" style="border-radius: 8px; font-size: 0px; line-height: 0px; valign: top;"> <img src="'.$imageUrl.'" width="169" border="0" style="display: block; width: 100%; border-radius: 8px;"></td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$title.' '.$intro.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px;">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%">
                                                            <tr>
                                                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$text.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="nl2go-responsive-hide">
                                                                                        <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="160" class="r16-o" style="table-layout: fixed; width: 160px;">
                                                                                    <tr>
                                                                                        <td height="18" align="center" valign="top" class="r17-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word;">
                                                                                            <!--[if mso]> 
                                                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'.$link.'" style="v-text-anchor:middle; height: 41px; width: 159px;" arcsize="20%" fillcolor="#04131b" strokecolor="#04131b" strokeweight="1px" data-btn="2">
                                                                                                <w:anchorlock> </w:anchorlock>
                                                                                                <v:textbox inset="0,0,0,0">
                                                                                                <div style="display:none;">
                                                                                                    <center class="default-button"><span>Read on here</span></center>
                                                                                                </div>
                                                                                                </v:textbox>
                                                                                            </v:roundrect>
                                                                                            <![endif]-->  <!--[if !mso]><!-- --> <a href="'.$link.'" class="r18-r default-button" data-btn="2" style="font-style: normal; font-weight: normal; line-height: 1.15; text-decoration: none; word-break: break-word; border-style: solid; word-wrap: break-word; display: block; -webkit-text-size-adjust: none; background-color: #04131b; border-color: #666666; border-radius: 8px; border-width: 0px; color: #ffffff; font-family: Arial; font-size: 16px; height: 18px; mso-hide: all; padding-bottom: 12px; padding-left: 0px; padding-right: 0px; padding-top: 12px; width: 160px;"> <span>Read on here</span></a> <!--<![endif]--> 
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>';
                    
            } else if ($blockType == 'td_feature') {
            
                    $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                    $title     = '<strong>'.$block['title'].'</strong>';
                    $intro     = '<p class="default">'.$block['intro'].'</p>';
                    $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                    $link      = trim($block['link']);
                    
                    $emailContent .= '
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                    <tr>
                        <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tr>
                                <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                        <tr>
                                            <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                <tr>
                                                    <td class="r11-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #d6f5e6; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                        <div>
                                                        <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/training" style="color:black;text-decoration:none;">Training Directory</a></strong></span></p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                            <tr>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r19-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r20-c" align="center" style="border-radius: 8px; font-size: 0px; line-height: 0px; valign: top;"> <img src="'.$imageUrl.'" width="169" border="0" style="display: block; width: 100%; border-radius: 8px;"></td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$title.' '.$intro.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px;">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%">
                                                            <tr>
                                                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$text.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="nl2go-responsive-hide">
                                                                                        <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="160" class="r16-o" style="table-layout: fixed; width: 160px;">
                                                                                    <tr>
                                                                                        <td height="18" align="center" valign="top" class="r17-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word;">
                                                                                            <!--[if mso]> 
                                                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'.$link.'" style="v-text-anchor:middle; height: 41px; width: 159px;" arcsize="20%" fillcolor="#04131b" strokecolor="#04131b" strokeweight="1px" data-btn="2">
                                                                                                <w:anchorlock> </w:anchorlock>
                                                                                                <v:textbox inset="0,0,0,0">
                                                                                                <div style="display:none;">
                                                                                                    <center class="default-button"><span>Read on here</span></center>
                                                                                                </div>
                                                                                                </v:textbox>
                                                                                            </v:roundrect>
                                                                                            <![endif]-->  <!--[if !mso]><!-- --> <a href="'.$link.'" class="r18-r default-button" data-btn="2" style="font-style: normal; font-weight: normal; line-height: 1.15; text-decoration: none; word-break: break-word; border-style: solid; word-wrap: break-word; display: block; -webkit-text-size-adjust: none; background-color: #04131b; border-color: #666666; border-radius: 8px; border-width: 0px; color: #ffffff; font-family: Arial; font-size: 16px; height: 18px; mso-hide: all; padding-bottom: 12px; padding-left: 0px; padding-right: 0px; padding-top: 12px; width: 160px;"> <span>Read on here</span></a> <!--<![endif]--> 
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>';
                    
            } else if ($blockType == 'cjs_information') {
            
                    $imageUrl  = 'https://www.countryside-jobs.com' . $block['image']['_default'];
                    $title     = '<strong>'.$block['title'].'</strong>';
                    $intro     = '<p class="default">'.$block['intro'].'</p>';
                    $text      = str_replace("<p>", '<p class="default">', $block['textarea']['raw']);
                    $link      = trim($block['link']);
                    
                    $emailContent .= '
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" align="center" class="r0-o" style="table-layout: fixed; width: 100%;">
                    <tr>
                        <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                <tr>
                                <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                        <tr>
                                            <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                <tr>
                                                    <td class="r11-c nl2go-default-textstyle" align="left" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; background-color: #e6ffd6; padding-bottom: 15px; padding-left: 10px; padding-right: 10px; padding-top: 15px; text-align: left; valign: top;">
                                                        <div>
                                                        <p style="margin: 0;"><span style="font-size: 26px;"><strong><a href="https://www.countryside-jobs.com/information/information-about-cjs" style="color:black;text-decoration:none;">CJS Information</a></strong></span></p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                            <tr>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r19-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r20-c" align="center" style="border-radius: 8px; font-size: 0px; line-height: 0px; valign: top;"> <img src="'.$imageUrl.'" width="169" border="0" style="display: block; width: 100%; border-radius: 8px;"></td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            <th width="50%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$title.' '.$intro.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r10-i" style="background-color: #ffffff; padding-bottom: 20px;">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%">
                                                            <tr>
                                                            <th width="100%" valign="top" class="r4-c" style="font-weight: normal;">
                                                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r5-o" style="table-layout: fixed; width: 100%;">
                                                                    <tr>
                                                                        <td valign="top" class="r9-i" style="padding-left: 15px; padding-right: 15px;">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r23-o" style="table-layout: fixed; width: 100%;">
                                                                                    <tr>
                                                                                        <td align="left" valign="top" class="r24-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left;">
                                                                                            <div>
                                                                                            '.$text.'
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="nl2go-responsive-hide">
                                                                                        <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="r15-c" align="left">
                                                                                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="160" class="r16-o" style="table-layout: fixed; width: 160px;">
                                                                                    <tr>
                                                                                        <td height="18" align="center" valign="top" class="r17-i nl2go-default-textstyle" style="color: #414141; font-family: Arial; font-size: 16px; line-height: 1.5; word-break: break-word;">
                                                                                            <!--[if mso]> 
                                                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'.$link.'" style="v-text-anchor:middle; height: 41px; width: 159px;" arcsize="20%" fillcolor="#04131b" strokecolor="#04131b" strokeweight="1px" data-btn="2">
                                                                                                <w:anchorlock> </w:anchorlock>
                                                                                                <v:textbox inset="0,0,0,0">
                                                                                                <div style="display:none;">
                                                                                                    <center class="default-button"><span>Read on here</span></center>
                                                                                                </div>
                                                                                                </v:textbox>
                                                                                            </v:roundrect>
                                                                                            <![endif]-->  <!--[if !mso]><!-- --> <a href="'.$link.'" class="r18-r default-button" data-btn="2" style="font-style: normal; font-weight: normal; line-height: 1.15; text-decoration: none; word-break: break-word; border-style: solid; word-wrap: break-word; display: block; -webkit-text-size-adjust: none; background-color: #04131b; border-color: #666666; border-radius: 8px; border-width: 0px; color: #ffffff; font-family: Arial; font-size: 16px; height: 18px; mso-hide: all; padding-bottom: 12px; padding-left: 0px; padding-right: 0px; padding-top: 12px; width: 160px;"> <span>Read on here</span></a> <!--<![endif]--> 
                                                                                        </td>
                                                                                    </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>';
                    
            }
            
        }
       
        $htmlTemplate = file_get_contents('../../email/email_template.html');
        $emailContent = str_replace("{% HTML %}", $emailContent, $htmlTemplate);
        
        // Save or Update Brevo Campaign
        if($data['campaignID']<1){
            //Save
            $curl = curl_init();
            
            curl_setopt_array($curl, [
              CURLOPT_URL => "https://api.brevo.com/v3/emailCampaigns",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode([
                'sender' => [
                    'name' => 'Countryside Jobs Service',
                    'email' => 'mailings@countrysidejobs.com'
                ],
                'recipients' => [
                    'listIds' => [
                            5
                    ]
                ],
                'inlineImageActivation' => false,
                'recurring' => false,
                'abTesting' => false,
                'ipWarmupEnable' => false,
                'htmlContent' => $emailContent,
                'name' => $data['subject'],
                'subject' => $data['subject'],
                'previewText' => 'CJS Daily Email containing jobs and news headlines.'
              ]),
              CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
                "content-type: application/json"
              ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
            
            $result = json_decode($response, true);
            $data['campaignID'] = $result['id'];
            
            if($data['status']=='scheduled'){
                $curl = curl_init();
                
                curl_setopt_array($curl, [
                  CURLOPT_URL => "https://api.brevo.com/v3/emailCampaigns/".$data['campaignID'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "PUT",
                  CURLOPT_POSTFIELDS => json_encode([
                    'scheduledAt' => $scheduledDateBrevo
                  ]),
                  CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
                    "content-type: application/json"
                  ],
                ]);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
            }
        }else{
            // Update
            $curl = curl_init();
            
            curl_setopt_array($curl, [
              CURLOPT_URL => "https://api.brevo.com/v3/emailCampaigns/".$data['campaignID'],
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => json_encode([
                'sender' => [
                    'name' => 'Countryside Jobs Service',
                    'email' => 'mailings@countrysidejobs.com'
                ],
                'recipients' => [
                    'listIds' => [
                            5
                    ]
                ],
                'inlineImageActivation' => false,
                'recurring' => false,
                'abTesting' => false,
                'ipWarmupEnable' => false,
                'htmlContent' => $emailContent,
                'name' => $data['subject'],
                'scheduledAt' => $scheduledDateBrevo,
                'subject' => $data['subject'],
                'previewText' => 'CJS Daily Email containing jobs and news headlines.'
              ]),
              CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
                "content-type: application/json"
              ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }

        }

        //print_r($result);
        
        //print_r($data);
        
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Email)) {
            $result = $Email->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['emailID'])) unset($data['emailID']);
        
            $new_email = $Emails->create($data);
        
            // SHOW RELEVANT MESSAGE
            if ($new_email) {
                PerchUtil::redirect($API->app_path() .'/email/add_new/?id='.$new_email->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that email could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your email has been successfully updated. Return to %semails%s', '<a href="'.$API->app_path() .'/email/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that email could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Email)) {
            $details = $Email->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if($_GET['templateID']){
        $eTemplate = $Templates->find($_GET['templateID'], true);
        $tDetails = $eTemplate->to_array();
        $details = PerchUtil::json_safe_decode($tDetails['templateDynamicFields'], true);
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your email has been successfully created. Return to %semail%s', '<a href="'.$API->app_path() .'/email/">', '</a>'); 
    }