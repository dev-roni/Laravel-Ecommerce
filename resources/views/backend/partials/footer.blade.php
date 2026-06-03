    </div>
    @include('backend/partials/alertMessage')
    <script src="{{asset('backend_assets/backend_js/admin.js')}}"></script>
    <script src="{{asset('backend_assets/backend_js/chart.js')}}"></script> <!-- চার্টের জন্য বিশেষ ফাইল -->
    <script src="{{asset('backend_assets/backend_js/bootstrap.bundle.min.js')}}"></script> <!-- চার্টের জন্য বিশেষ ফাইল -->
    @stack('scripts')
</body>
</html>
