<div class="modal fade" id="dashboard-upgrade-modal" tabindex="-1" role="dialog" aria-labelledby="dashboard-upgrade-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="dashboard-upgrade-modalLabel" v-html="upgrade_subscription.title"></h4>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body">
        You are currently on the <strong>@{{ upgrade_subscription.package_old }}</strong> package.<br/><br/>
        The new <strong>@{{ upgrade_subscription.package_new }}</strong> package would upgrade the following modules:


        Upgrade cost is N (monthly) or N (annually)
        
        <div id="upgrade_body" name="upgrade_body" v-html="upgrade_subscription.body"></div>

      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <a :href="upgrade_subscription.action_url" name="upgrade_action" value="1" id="upgrade_action" class="btn btn-primary" v-html="upgrade_subscription.action" v-if="upgrade_subscription !== ''"></a>
      </div>
    </div>
  </div>
</div>