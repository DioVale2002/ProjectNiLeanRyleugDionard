@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="font-sans">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Add New Product</h2>
            <p class="text-sm text-gray-500 mt-1">Create a new book listing in the catalog.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm">
            ← Back to Inventory
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Basic Details Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Basic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Title</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="Title" value="{{ old('Title') }}" placeholder="Enter book title" required />
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="Author" value="{{ old('Author') }}" placeholder="Author name" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="ISBN" value="{{ old('ISBN') }}" placeholder="Ex: 978-3-16-148410-0" required />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="Publisher" value="{{ old('Publisher') }}" placeholder="Publisher name" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="Genre" value="{{ old('Genre') }}" placeholder="e.g. Fiction, Engineering" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age Group</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="text" name="Age_Group" value="{{ old('Age_Group') }}" placeholder="e.g. Teen, Adult, College" />
                        </div>
                    </div>
                </div>

                {{-- Descriptions Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Descriptions</h3>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description (Review)</label>
                        <textarea class="w-full rounded-lg border border-gray-300 p-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors resize-none" placeholder="A brief summary for the product card..." name="Review" rows="3">{{ old('Review') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Long Description</label>
                        <textarea class="w-full rounded-lg border border-gray-300 p-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors resize-none" placeholder="Full book description..." name="LongReview" rows="5"></textarea>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">
                
                {{-- Pricing & Stock Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Inventory & Pricing</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors font-semibold" type="number" name="Price" step="0.01" min="0" value="{{ old('Price') }}" placeholder="0.00" required />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Initial Stock</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" type="number" name="Stock" min="0" value="{{ old('Stock') }}" placeholder="0" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Length (in)</label>
                                <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] transition-colors" type="number" name="Length" min="0" value="{{ old('Length') }}" placeholder="0" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Width (in)</label>
                                <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] transition-colors" type="number" name="Width" min="0" value="{{ old('Width') }}" placeholder="0" />
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Initial Rating</label>
                            <input class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] transition-colors" type="number" name="Rating" step="0.01" min="0" max="5" value="{{ old('Rating') }}" placeholder="0.0 to 5.0" />
                        </div>
                    </div>
                </div>

                {{-- Media Upload Card with JS Preview --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Product Image</h3>
                    
                    <label for="file-upload" class="relative flex flex-col items-center justify-center w-full h-[280px] rounded-xl border-2 border-dashed border-gray-300 transition-all hover:border-[#FCAE42] hover:bg-orange-50/50 cursor-pointer group overflow-hidden">
                        
                        {{-- The container that will be replaced by the image --}}
                        <div id="image-preview-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 w-full h-full pointer-events-none">
                            <div class="mb-4 rounded-full bg-orange-100 p-4 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-[#FCAE42]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-base font-semibold text-gray-700 mb-1">Click to upload</span>
                            <p class="text-center text-xs text-gray-500">PNG, JPG up to 2MB<br/>Recommended 600x800</p>
                        </div>
                        
                        <input id="file-upload" name="image" type="file" accept="image/png, image/jpeg" class="opacity-0 w-full h-full cursor-pointer absolute inset-0" />
                    </label>
                </div>

                {{-- Action Button --}}
                <button type="submit" class="w-full py-3.5 rounded-xl bg-[#F54E4E] hover:bg-red-600 transition-colors text-white font-bold text-lg shadow-sm flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Save & Publish Product
                </button>

            </div>
        </div>
    </form>
</div>

{{-- JS for Image Preview --}}
<script>
    document.getElementById('file-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById('image-preview-container');
                previewContainer.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview" />
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                        <span class="text-white font-medium px-4 py-2 bg-black/50 rounded-lg">Change Image</span>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection