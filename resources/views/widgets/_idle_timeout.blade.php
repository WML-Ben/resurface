<div class="modal fade" id="lockoutModal" tabindex="-1" role="dialog" aria-labelledby="lockoutModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Session Expiration Warning</h4>
            </div>
            <div class="modal-body">
                <p>You've been inactive for a while. For your security, we'll log you out automatically. Click "Stay Online" to continue your session. </p>
                <p>Your session will expire in <span class="fwb" id="sessionSecondsRemaining">120</span> seconds.</p>
            </div>
            <div class="modal-footer">
                <button id="extendSession" type="button" class="btn btn-default btn-success" data-dismiss="modal">Stay Online</button>
                <button id="logoutSession" type="button" class="btn btn-default" data-dismiss="modal">Logout</button>
            </div>
        </div>
    </div>
</div>

{!!Form::open(['route' => 'lockout', 'id' => 'lockoutForm']) !!}{!! Form::close() !!}

{!! Html::script($siteUrl . '/js/idle-timer.min.js') !!}

<script>
    (function ($) {
        var session = {
            // Logout Settings
            inactiveTimeout: Number("{{ $idleTimeOut }}"), // (ms) The time until we display a warning message
            warningTimeout: 15000,          // (ms) The time until we log them out
            minWarning: 5000,               // (ms) If they come back to page (on mobile), The minumum amount, before we just log them out
            warningStart: null,             // Date time the warning was started
            warningTimer: null,             // Timer running every second to countdown to logout
            logout: function () {           // Logout function once warningTimeout has expired
                $('#lockoutForm').submit();
            },
            // keepAlive Settings
            keepAliveTimer: null,
            keepAliveUrl: "",
            keepAliveInterval: 5000,     //(ms) the interval to call said url
            keepAlive: function () {
                $.ajax({ url: session.keepAliveUrl });
            }
        };

        $(document).on('idle.idleTimer', function (event, elem, obj) {
            // Get time when user was last active
            var
                diff = (+new Date()) - obj.lastActive - obj.timeout,
                warning = (+new Date()) - diff;

            // On mobile js is paused, so see if this was triggered while we were sleeping
            if (diff >= session.warningTimeout || warning <= session.minWarning) {
                session.logout();
            } else {
                // Show dialog, and note the time
                $('#sessionSecondsRemaining').html(Math.round((session.warningTimeout - diff) / 1000));
                $('#lockoutModal').modal('show');
                session.warningStart = (+new Date()) - diff;

                // Update counter downer every second
                session.warningTimer = setInterval(function () {
                    var remaining = Math.round((session.warningTimeout / 1000) - (((+new Date()) - session.warningStart) / 1000));
                    if (remaining >= 0) {
                        $('#sessionSecondsRemaining').html(remaining);
                    } else {
                        session.logout();
                    }
                }, 1000)
            }
        });

        // Create a timer to keep server session alive, independent of idle timer
        session.keepAliveTimer = setInterval(function () {
            session.keepAlive();
        }, session.keepAliveInterval);

        // User clicked ok to extend session
        $('#extendSession').click(function () {
            clearTimeout(session.warningTimer);
        });
        // User clicked logout
        $('#logoutSession').click(function () {
            session.logout();
        });

        // Set up the timer, if inactive for 10 seconds log them out
        $(document).idleTimer(session.inactiveTimeout);
    })(jQuery);

</script>