<div class="box counter">
    <div class="counter__content">
                                <!-- Calender component -->
                                <ul class="counter__month"></ul>

                                <!-- Counter component -->
                                <div class="counter__countdown">
                                    <ul class="counter__countdown-timer">
                                        <li id="days">000<span>days</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="hours">00<span>hours</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="minutes">00<span>minutes</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="seconds">00<span>seconds</span></li>
                                        <li id="ongoingLabel" style="display: none;">On Going 🎯</li>
                                    </ul>
                                    <div class="counter__countdown-decoration">
                                        <div class="counter__countdown-decoration-arrow"></div>
                                        <div class="counter__countdown-decoration-arrow"></div>
                                        <div class="counter__countdown-decoration-arrow"></div>
                                    </div>
                                    <div class="counter__countdown-percent">0<span>%</span></div>
                                </div>

                                <!-- Progresbar component -->
                                <div class="counter__progressbar">
                                    <progress id="progressBar" class="counter__progress" max="100" value="0"></progress>
                                    <div id="endTip"></div>
                                </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getNumericValue(id) {
        var el = document.getElementById(id);
        if (!el) return 0;
        var text = el.textContent || '';
        var num = parseInt(text, 10);
        return isNaN(num) ? 0 : num;
    }

    function toggleCountdownState() {
        var days = getNumericValue('days');
        var hours = getNumericValue('hours');
        var minutes = getNumericValue('minutes');
        var seconds = getNumericValue('seconds');

        var progressEl = document.getElementById('progressBar');
        var progressVal = 0;
        if (progressEl) {
            // Support both attribute and property value
            var attrVal = progressEl.getAttribute('value');
            progressVal = parseFloat(attrVal || progressEl.value || 0) || 0;
        }

        var ended = (progressVal >= 100) || (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0);

        // Toggle visibility of countdown items
        ['days', 'hours', 'minutes', 'seconds'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.style.display = ended ? 'none' : '';
        });
        document.querySelectorAll('.counter__countdown-colon').forEach(function (el) {
            el.style.display = ended ? 'none' : '';
        });

        // Toggle On Going label
        var ongoing = document.getElementById('ongoingLabel');
        if (ongoing) ongoing.style.display = ended ? '' : 'none';
    }

    // Initial run and periodic check while counting progresses
    toggleCountdownState();
    setInterval(toggleCountdownState, 1000);
});
</script>
@endpush
    </div>
</div>
