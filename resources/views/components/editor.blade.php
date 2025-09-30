<!-- resources/views/components/simple-tabs.blade.php -->
<div class="w-full">
    <!-- Tabs -->
    <div class="flex border-b  border-b-gray-300 mb-4 space-x-2">
        <button type="button" class="px-6 py-3 tab-btn rounded-t-lg transition-all duration-200" data-tab="editor">
            Editor
        </button>
        <button type="button" class="px-6 py-3 tab-btn rounded-t-lg transition-all duration-200" data-tab="html">
            HTML
        </button>
        <button type="button" class="px-6 py-3 tab-btn rounded-t-lg transition-all duration-200" data-tab="preview">
            {{ __('Preview') }}
        </button>
    </div>

    <!-- Tab content -->
    <div id="editor-tab" class="tab-content">
        <div class="border min-h-[300px] border-gray-300 rounded-lg p-4 bg-white w-full" id="editor">
            <h2 class="text-lg font-semibold mb-2">Demo Content</h2>
            <p class="text-gray-600">Preset built with <code>snow</code> theme, and some common formats.</p>
        </div>
    </div>

    <div id="html-tab" class="tab-content hidden">
        <div class="border border-gray-300 rounded-lg w-full">
            <!-- HTML textarea -->
            <textarea
                class="w-full min-h-[300px] px-4 py-3 border  border-gray-300 rounded-lg font-mono text-sm  text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-vertical"
                name="{{ $name }}" id="content">{!! $content !!}</textarea>
        </div>
    </div>

    <div id="preview-tab" class="tab-content hidden ql-container ql-snow">
        <div class="border min-h-[300px] ql-editor border-gray-300 rounded-lg p-4 bg-white prose max-w-none w-full">
            <div id="preview"></div>
        </div>
    </div>

</div>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            // Tabs
            const tabs = document.querySelectorAll(".tab-btn");
            const tabContents = document.querySelectorAll(".tab-content");

            tabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    const target = this.getAttribute("data-tab");

                    // Hide all contents
                    tabContents.forEach(tc => tc.classList.add("hidden"));

                    // Show target tab
                    document.getElementById(target + "-tab").classList.remove("hidden");

                    // Update active tab styles
                    tabs.forEach(t => t.classList.remove(
                        "border-b-2", "border-blue-500", "text-blue-600", "bg-white",
                        "shadow"
                    ));
                    this.classList.add(
                        "border-b-2", "border-blue-500", "text-blue-600", "bg-white", "shadow"
                    );
                });
            });

            // Open editor by default
            document.querySelector('[data-tab="editor"]').click();

            // Init Quill
            const quill = new Quill('#editor', {
                theme: "snow",
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, 3, 4, 5, 6, false]
                        }],
                        ["bold", "italic", "underline", "strike"],
                        [{
                            script: "sub"
                        }, {
                            script: "super"
                        }],
                        [{
                            indent: "-1"
                        }, {
                            indent: "+1"
                        }],
                        [{
                            direction: "rtl"
                        }],
                        [{
                            size: ["small", false, "large", "huge"]
                        }],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            font: []
                        }],
                        [{
                            align: []
                        }],
                        ["blockquote", "code-block"],
                        ["link"],
                        ["clean"],
                    ],
                },
                placeholder: "{{ __('Start writing your content here...') }}",
            });


            quill.on("text-change", () => {
                document.getElementById("content").value = quill.root.innerHTML;
                document.getElementById("preview").innerHTML = quill.root.innerHTML
            });

            document.getElementById("content").addEventListener("blur", function(textarea) {
                quill.root.innerHTML = textarea.target.value;
            })

            // Load content to start
            quill.root.innerHTML = document.getElementById("content").value;
        });
    </script>
@endpush
