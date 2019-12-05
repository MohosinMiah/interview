@extends('layouts.app')
@section('content')
<?php  

use Bulkly\SocialPostGroups;
use Bulkly\SocialAccounts;
?>
    <div class="container-fluid app-body">
        <div class="row">
        <table class="table">
  <thead>
    <tr>
      <th scope="col">Group Name</th>
      <th scope="col">Group Type</th>
      <th scope="col">Account Name</th>
      <th scope="col">Post Text</th>
      <th scope="col">Time</th>
    </tr>
  </thead>
  <tbody>
  <?php   
     foreach($posts as $post){
     ?>
    <tr>
    
    <td><?php  
  $group_name = SocialPostGroups::where('id',$post->group_id)->first();
//   DB::table('users')->where('name', 'John')->first();
        echo $group_name->name;
    
    ?></td>
    <td><?php 
        
            echo $group_name->type;
    ?></td>
    <td>
    <?php 
       $user_profile = SocialAccounts::where('user_id',$post->user_id)->first();
       switch($post->account_service){
           case "facebook":
            echo '<i class="fa fa-facebook"></i>';
            break;
            case "twitter":
            echo '<i class="fa fa-twitter"></i>';
            break;        
            case "google":
            echo '<i class="fa fa-google"></i>';
            break;
            case "instagram":
            echo '<i class="fa fa-instagram"></i>';
            break;
            case "linkedin":
            echo '<i class="fa fa-linkedin"></i>';
            break;

       }
    ?>
          
          
        <img src="{{$user_profile->avatar}}" alt="User Avatar" width="80px" height="80px">

    <!-- {{$post->account_service}} -->
    
    </td>
    <td>{{str_limit($post->post_text,40)}}</td>
    <td>{{ $post->sent_at}}</td>
    
    </tr>
    <?php }  ?>
  </tbody>
</table>
    <script>
        var allSelection = 0;
        // ==========================
        // Group wise Group Delete
        // ==========================
        function removeGroupIds(trigger, e) {
            e.stopPropagation();
            e.preventDefault();
            var ids = [];
            var idsContent = $('.bl-selected');
            $.each(idsContent, function (i, v) {
                ids.push($(v).attr('data-id'));
            });
            var form = $('#group-bl-ids');
            form.find('input[name="ids"]').val(ids.join(','));
            var data = form.serializeArray();
            $.ajax({
                type: "POST",
                url: '/group-delete/selected',
                data: data,
                success: function success(msg) {
                    window.location.href = '';
                },
                error: function error(xhr, ajaxOptions, thrownError) {
                    alert('Something is not right. Please try again.')
                }
            });
        }

        function reqForEdit() {
            var modal = $('#group-ids-modal');
            var ids = $('.bl-selected');
            if (ids.length > 0) {
                modal.modal('show');
            }
        }
        function deselectAll() {
            $('.group-single').removeClass('bl-selected');
            allSelection = 0;
            $('#allGroup-Selection').prop('checked', false);
            $('#pendingGroup-Selection').prop('checked', false);
            $('#activeGroup-Selection').prop('checked', false);
            $('#completedGroup-Selection').prop('checked', false);
        }
        function selectThisGroupType(){
            var all = $('#allGroup-Selection');
            var pendingV = $('#pendingGroup-Selection').prop('checked');
            var activeV = $('#activeGroup-Selection').prop('checked');
            var completedV = $('#completedGroup-Selection').prop('checked');

            if(pendingV===true && activeV===true && completedV===true){
                all.prop('checked', true);
                $('.group-single').removeClass('bl-selected').addClass('bl-selected');
            } else {
                all.prop('checked', false);
                if(pendingV === true){
                    $('.group-items[data-status="pending"]').find('.group-single').removeClass('bl-selected').addClass('bl-selected');
                } else {
                    $('.group-items[data-status="pending"]').find('.group-single').removeClass('bl-selected');
                }
                if(activeV === true){
                    $('.group-items[data-status="active"]').find('.group-single').removeClass('bl-selected').addClass('bl-selected');
                } else {
                    $('.group-items[data-status="active"]').find('.group-single').removeClass('bl-selected');
                }
                if(completedV === true){
                    $('.group-items[data-status="completed"]').find('.group-single').removeClass('bl-selected').addClass('bl-selected');
                } else {
                    $('.group-items[data-status="completed"]').find('.group-single').removeClass('bl-selected');
                }
            }
        }
        function selectAllGroupType(){
            var all = $('#allGroup-Selection');
            var pending = $('#pendingGroup-Selection');
            var active = $('#activeGroup-Selection');
            var completed = $('#completedGroup-Selection');

            if(all.prop('checked')===true){
                pending.prop('checked', true);
                active.prop('checked', true);
                completed.prop('checked', true);
                $('.group-single').removeClass('bl-selected').addClass('bl-selected');
            } else {
                pending.prop('checked', false);
                active.prop('checked', false);
                completed.prop('checked', false);
                $('.group-single').removeClass('bl-selected');
            }
        }
        function selectThisGroup(trigger, e) {
            if (e.ctrlKey) {
                e.stopPropagation();
                e.preventDefault();
                var trigger = $(trigger);
                var type = trigger.attr('data-select');
                if (type != 1) {
                    trigger.attr('data-select', 1);
                    trigger.removeClass('bl-selected');
                    trigger.addClass('bl-selected');
                } else {
                    trigger.attr('data-select', 0);
                    trigger.removeClass('bl-selected');
                }
                if(allSelection === 0){
                    allSelection = 1;
                    $('#group-ids-selection-modal').modal('show');
                }
            }
        }
    </script>

@endsection
