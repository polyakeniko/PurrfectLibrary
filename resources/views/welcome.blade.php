<x-app-layout>
    <div
        class="relative overflow-hidden bg-cover bg-no-repeat p-12 text-center"
        style="background-image: url('../../images/homewelcome.jpg'); background-size: cover; height: 400px;">
        <div
            class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-fixed"
            style="background-color: rgba(0, 0, 0, 0.6)">
            <div class="flex h-full items-center justify-center">
                <div class="text-white">
                    <h2 class="mb-4 text-5xl font-semibold">Welcome to PurrfectLibrary</h2>
                    <h4 class="mb-6 text-xl font-semibold">Whether you're a seasoned feline parent or simply adore all things cat-related, <br>this library brings together a collection of books, articles, and resources dedicated to the world of cats.</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="section" style="background-color: #6b4c33">
        <div class="container mx-auto">
            <div class="row justify-center">
                <div class="col-sm-12">
                    <div class="mt-0 md:mt-13 flex flex-wrap justify-center">
                        <div class="w-full sm:w-1/4 p-0">
                            <div class="relative bg-cover bg-center pt-3 pb-3 px-2 " style="background-image: url('../../images/book1.jpg');">
                                <div class="absolute inset-0 bg-orange-400 opacity-50"></div>
                                <h2 class="text-5xl text-white font-extrabold relative z-10">01</h2>
                                <h3 class="text-lg text-white font-bold relative z-10">Search through popular books</h3>
                                <p class="text-white relative z-10">Find your favourite books from our wide variety. Scroll through popular and new books separately.</p>
                            </div>
                        </div>
                        <div class="w-full sm:w-1/4 p-0">
                            <div class="relative bg-cover bg-center pt-3 pb-3 px-2  md:pe-10 lg:pe-20 sm:pe-8" style="background-image: url('../../images/book2.jpg');">
                                <div class="absolute inset-0 bg-orange-600 opacity-50"></div>
                                <h2 class="text-5xl text-white font-extrabold relative z-10">02</h2>
                                <h3 class="text-lg text-white font-bold relative z-10">Borrow books from us</h3>
                                <p class="text-white relative z-10">If you found the book you have searched for, book it at a discounted price.</p>
                            </div>
                        </div>
                        <div class="w-full sm:w-1/4 p-0">
                            <div class="relative bg-cover bg-center pt-3 pb-3 px-2 " style="background-image: url('../../images/book3.jpg');">
                                <div class="absolute inset-0 bg-orange-950 opacity-50"></div>
                                <h2 class="text-5xl text-white font-extrabold relative z-10">03</h2>
                                <h3 class="text-lg text-white font-bold relative z-10">Come and meet with cats</h3>
                                <p class="text-white relative z-10">You want to meet with cats? You're at the right place! Come to our us and read while petting cats.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section text-center bg-white">
        <div class="w-full">
            <div class="flex h-full items-center justify-center">
                <div class="text-white">
                    <h2 class="mb-4 text-4xl font-bold mt-5">Read more about our work</h2>
                    <h4 class="mb-6 text-xl font-semibold">
                        Whether you're a seasoned feline parent or simply adore all things cat-related,<br>
                        this library brings together a collection of books, articles, and resources dedicated to the world of cats.
                    </h4>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-10 mt-10">
                <div class="flex flex-col items-center bg-white text-black p-5 rounded-lg shadow-md w-full md:w-1/3  xl:mb-10 introduction">
                    <h2 class="text-xl font-medium text-white mb-3">Our Employees</h2>
                    <img src="../../images/employees.jpg" alt="Cat Image 1" class="mb-4 w-full h-60 object-cover rounded-lg">
                    <p class="text-lg text-white">At <b>PurrfectLibrary</b>, our employees are more than just book enthusiasts – they are passionate caretakers of stories, knowledge, and a vibrant community spirit. Each member of our team brings a unique blend of skills, creativity, and dedication to ensure every visitor's experience is truly "purrfect."</p>
                </div>
                <div class="flex flex-col items-center bg-white text-black p-5 rounded-lg shadow-md w-full md:w-1/3 xl:mb-10 introduction">
                    <h2 class="text-xl font-medium text-white mb-3">PurrfectLibrary's past</h2>
                    <img src="../../images/timeline.jpg" alt="Cat Image 2" class="mb-4 w-full h-60 object-cover rounded-lg">
                    <p class="text-lg text-white">Founded in the heart of our community, <b>PurrfectLibrary</b> began as a small, cozy space dedicated to bringing people and stories together. Its origins trace back to 2000, when a group of book lovers envisioned a haven where knowledge, imagination, and connection could flourish.</p>
                </div>
            </div>
        </div>
    </div>
    <div
        class="relative overflow-hidden bg-cover bg-no-repeat p-12 text-center"
        style="background-image: url('/images/book4.jpg'); height: 400px">
        <div
            class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-fixed"
            style="background-color: rgba(0,0,0,0.6)">
            <div class="flex h-full items-center justify-center">
                <div class="text-white">
                    <h2 class="mb-4 text-4xl font-semibold">You don't have an account yet?</h2>
                    <h4 class="mb-6 text-xl font-semibold">Sign up to our page so you can access <br> more of our site's functions.</h4>
                    <a href="/register">
                        <button
                            type="button"
                            class="animation rounded-lg border-2 border-neutral-50 px-10 py-4 text-lg font-bold uppercase leading-normal text-neutral-50 transition duration-150 ease-in-out hover:border-neutral-100 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-neutral-100 focus:border-neutral-100 focus:text-neutral-100 focus:outline-none focus:ring-0 active:border-neutral-200 active:text-neutral-200 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10">
                            Sign Up
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
