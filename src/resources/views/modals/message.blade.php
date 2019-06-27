<div class="modal fade" id="dashboard-message-modal" tabindex="-1" role="dialog" aria-labelledby="dashboard-message-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="dashboard-message-modalLabel" v-html="dashboard_message.title"></h4>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body">
        
        <div id="message_body" name="message_body" v-html="dashboard_message.body"></div>

      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <a :href="dashboard_message.action_url" name="message_action" value="1" id="message_action" class="btn btn-primary" v-html="dashboard_message.action" v-if="dashboard_message !== ''"></a>
      </div>
    </div>
  </div>
</div>