<body>

    @if(request('verified'))
        <div id="alert-message" class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
            role="alert" style="z-index: 1050; min-width: 250px;">
            Verified Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-shopping-bag me-2"></i>পবনবাহিকা
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">হোম</a></li>
                    <li class="nav-item"><a class="nav-link" href="#categories">ক্যাটাগরি</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">প্রোডাক্ট</a></li>
                    @if(!auth()->check())
                    <li class="nav-item"><a class="nav-link" href="{{route('login')}}">লগইন</a></li>
                    @else
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="nav-link">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent text-white w-100 text-start p-0">
                                <i class="fas fa-sign-out-alt"></i> লগআউট
                            </button>
                        </form>
                    </li>
                    @endif
                </ul>
                <div class="navbar-nav ms-3">
                    <a class="nav-link" href="#"><i class="fas fa-search"></i></a>
                    <a class="nav-link position-relative" href="#">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-accent">3</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>