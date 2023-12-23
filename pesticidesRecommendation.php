<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['image_upload'])) {
        // Handle image upload from the form
        if (isset($_FILES['image'])) {
            $baseURL = "https://disease-detection-jcpb.onrender.com";
            $imageUploadURL = $baseURL . "/predict";

            // Check if the file was uploaded without errors
            if ($_FILES['image']['error'] === 0) {
                $imagePath = $_FILES['image']['tmp_name'];
                $response = uploadImage($imageUploadURL, $imagePath);
                // Handle the response file
                $result = handleResponseFile($response);
            } else {
                $result = "<div class='alert alert-danger alert-dismissible fade-in'>ফিল্ডটি খালি রয়েছে, রোগাক্রান্ত পাতার ছবি আপলোড করুন!</div>";
            }
        }
    } elseif (isset($_POST['image_url_submit'])) {
        // Handle image URL submission from the form
        $baseURL = "https://disease-detection-jcpb.onrender.com";
        $imageURL = $_POST['image_url'];
        $imageURLUploadURL = $baseURL . "/predict/image-url=" . urlencode($imageURL);
        $response = uploadImage($imageURLUploadURL, $imageURL);
        // Handle the response file
        $result = handleResponseFile($response);
    }
    sleep(2);
}

// Function to send a POST request with an image file
function uploadImage($url, $imagePath) {
    $cFile = curl_file_create($imagePath, mime_content_type($imagePath), basename($imagePath));
    
    $data = array('file' => $cFile);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Function to handle the response file
function handleResponseFile($response) {
    $decodedResponse = json_decode($response, true);
    $filename = $decodedResponse['filename'];
    $baseURL = "https://disease-detection-jcpb.onrender.com";
    $fetchDataURL = $baseURL . "/data/filename=" . $filename;
    $imageURL = "https://disease-detection-jcpb.onrender.com/data/" . $filename;

    $baseURL = "https://disease-detection-jcpb.onrender.com";
    $fetchDataURL = $baseURL . "/data/filename=" . $filename;
    $predictionData = getPredictionData($fetchDataURL);

    $result = "<h2>Uploaded Image:</h2>";
    $result .= "<img src='" . $imageURL . "' alt='Uploaded Image' style='max-width: 500px; max-height: 500px;'>";
    $result .= "<h2>Response from Image Upload:</h2>";
    $result = "<table class='table table-striped'>";

    $decodedPredictionData = json_decode($predictionData, true);
    foreach ($decodedPredictionData as $key => $value) {

       
        $vls = explode(":", $value)[1];

        $vls = str_replace('"', '', $vls);
        $vls = str_replace('}', '', $vls);
        
        //Bean Angular Leaf Spot
        if(trim($vls) =="Bean Angular Leaf Spot") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>শিমের কৌণিক পাতার দাগ।<br>
                <strong>কীটনাশকের নাম: </strong>কার্বেন্ডাজিম, কপার হাইড্রক্সাইড, মানকোজেব।<br>                
                <div class='mt-2 text-center'>
                <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>কার্বেন্ডাজিমগ্রুপের (ডেরোসাল, ব্যাভিস্টিন) প্রতি শতক জমিতে ২.৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। কপার হাইড্রক্সাইডগ্রুপের (কোসিড, চ্যাম্প) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।        
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //Bean Bean Rust
        elseif(trim($vls) =="Bean Bean Rust") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>শিমের মরিচা।<br>
                <strong>কীটনাশকের নাম: </strong>প্রোপিকোনাজল, টেবুকোনাজল, ট্রাইফ্লোক্সিস্ট্রবিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>প্রপিকোনাজলগ্রুপের ছত্রাকনাশক (টিল্ট, ব্যানার ম্যাক্স) প্রতি শতক জমিতে ১.২৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। টেবুকোনাজলগ্রুপের ছত্রাকনাশক (ফলিকুর, এলিট) প্রতি শতক জমিতে  ২ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। ট্রাইফ্লোক্সিস্ট্রবিনগ্রুপের ছত্রাকনাশক (টিল্ট, ব্যানার ম্যাক্স) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //Rice Bacterial Leaf Blight
        elseif(trim($vls) =="Rice Bacterial Leaf Blight") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>পেডি ব্যাকটেরিয়াল লিফ ব্লাইট।<br>
                <strong>কীটনাশকের নাম: </strong>স্ট্রীপটোমাইসিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>পেডি ব্যাকটেরিয়াল লিফ ব্লাইট রোগের জন্য স্ট্রীপটোমাইসিন কীটনাশক(ব্লাসটিসিডিন অথবা ব্লাসটিসিডিন ১ গ্রাম অথবা স্ট্রীপটোমাইসিন সালফেট ২গ্রাম) ১ লিটার পানিতে মিশিয়ে শতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //Rice Brown Spot
        elseif(trim($vls) =="Rice Brown Spot") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>পেডি ব্রাউন স্পট।<br>
                <strong>কীটনাশকের নাম: </strong>ট্রীসাইক্লাজল।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>পেডি ব্রাউন স্পট রোগের জন্য ট্রীসাইক্লাজল কীটনাশক (বেলিটন অথবা ট্রিফরিন ০.৫গ্রাম ২ লিটার পানিতে মিশিয়ে অথবা ট্রিজল ১গ্রাম  ৩ লিটার পানিতে মিশিয়ে)শতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }

        //ধানের পাতার ঝাপটা।
        elseif(trim($vls) =="Rice Healthy") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>ধানের পাতার ঝাপটা।<br>
                <strong>কীটনাশকের নাম: </strong>কারবেনডাজিম।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ধানের পাতার ঝাপটা রোগের জন্য কারবেনডাজিম কীটনাশক (বেভিসটিন অথবা ভিটাভেক্স ০.৫গ্রাম  অথবা ফানডাজল ১গ্রাম  ) ১ লিটার পানিতে মিশিয়েশতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //ফুলকপি অল্টারনারিয়া পাতার দাগ।
        elseif(trim($vls) =="Rice Hispa") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>ফুলকপি অল্টারনারিয়া পাতার দাগ।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ফুলকপি অল্টারনারিয়া পাতার দাগ রোগের জন্য ক্লোরোথালোনিল কীটনাশক(ব্রাভো অথবা ফলিকুর ০৯ গ্রাম  ৩ লিটার পানিতে মিশিয়ে অথবা ইকো ১১ গ্রাম  ৫ লিটার পানিতে মিশিয়ে)শতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //ফুলকপি বাধাকপি এফিড কলোনি।
        elseif(trim($vls) =="Rice Leaf Smut") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>ফুলকপি বাধাকপি এফিড কলোনি।<br>
                <strong>কীটনাশকের নাম: </strong>পাইরিথ্রইডস।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ফুলকপি বাধাকপি এফিড কলোনি রোগের জন্য পাইরিথ্রইডস কীটনাশক(এমবুশ অথবা পারমিথ্রিন অথবা বেইথরিওড অথবা ইসফিনভালিরেট  ১ মি.লি    ৪ লিটার পানিতে মিশিয়ে)শতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //ফুলকপির রিং স্পট।
        elseif(trim($vls) =="Rice Leaf Blast") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>ফুলকপির রিং স্পট।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল, মেটালেক্সএল-এম।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ফুলকপির রিং স্পট রোগের জন্য ক্লোরোথালোনিল কীটনাশক(ব্রাভো ০৭ গ্রাম অথবা ফলিকুর ৭ মি.লি ৪ লিটার পানিতে মিশিয়ে অথবা ইকো অথবা টিল্ট  ৯ মি.লি  ৫ লিটার পানিতে মিশিয়ে)শতক প্রতি স্প্রে করতে হবে৷ ফুলকপির রিং স্পটরোগের জন্য মেটালেক্সএল-এম কীটনাশক(রিডোমিল গোল্ড এমজেড ১.৫ গ্রাম ৮ লিটার পানিতে মিশিয়ে অথবা একরোবেট এমজেড  ১.৫ গ্রাম ১০ লিটার পানিতে মিশিয়ে)শতক প্রতি স্প্রে করতে হবে৷ 
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো ব্যাকটেরিয়াল স্পট।
        elseif(trim($vls) =="Wheat Brown Rust") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো ব্যাকটেরিয়াল স্পট।<br>
                <strong>কীটনাশকের নাম: </strong>কপার হাইড্রক্সাইড, মানকোজেব, স্ট্রেপ্টোমাইসিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>কপার হাইড্রক্সাইডগ্রুপের (কোসিড, চ্যাম্প) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। স্ট্রেপ্টোমাইসিনগ্রুপের (এগ্রি-মাইসিন-১৭) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো আর্লি ব্লাইট।
        elseif(trim($vls) =="Wheat Healthy") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো আর্লি ব্লাইট।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল, মানকোজেব, অ্যাজোক্সিস্ট্রোবিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ক্লোরোথালোনিলগ্রুপের (ব্রাভো, ডাকনিল) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। অ্যাজোক্সিস্ট্রোবিনগ্রুপের (কোয়াড্রিস) প্রতি শতক জমিতে ২.৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো লেট ব্লাইট।
        elseif(trim($vls) =="Wheat Yellow Rust") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো লেট ব্লাইট।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল, মানকোজেব, মেফেনক্সাম।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ক্লোরোথালোনিলগ্রুপের (ব্রাভো,ইকো, ডাকনিল) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মেফেনক্সামগ্রুপের (রিডোমিল গোল্ড এমজেড) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো পাতার ছাঁচ।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো পাতার ছাঁচ।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল, মানকোজেব, কপার হাইড্রক্সাইড, টেবুকোনাজল।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ক্লোরোথালোনিলগ্রুপের (ব্রাভো,ইকো) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। কপার হাইড্রক্সাইডগ্রুপের (কোসিড, চ্যাম্প) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। টেবুকোনাজলগ্রুপের (লুনা) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো সেপ্টোরিয়া পাতার দাগ।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো সেপ্টোরিয়া পাতার দাগ।<br>
                <strong>কীটনাশকের নাম: </strong>অ্যাজোক্সিস্ট্রোবিন, লোরোথালোনিল, মানকোজেব, প্রোপিকোনাজল।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>অ্যাজোক্সিস্ট্রোবিনগ্রুপের (কোয়াড্রিস, আমিস্টার) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। ক্লোরোথালোনিলগ্রুপের (ব্রাভো,ইকো) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। প্রপিকোনাজলগ্রুপের ছত্রাকনাশক (টিল্ট, ব্যানার ম্যাক্স) প্রতি শতক জমিতে ১.২৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো স্পাইডার মাইটস।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো স্পাইডার মাইটস।<br>
                <strong>কীটনাশকের নাম: </strong>অ্যাবামেকটিন, বাইফেনথ্রিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>অ্যাবামেকটিনগ্রুপের (আভিদ) প্রতি শতক জমিতে ০.৩২ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। বাইফেনথ্রিনগ্রুপের (ব্রিগেড) প্রতি শতক জমিতে ১.২৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো টার্গেট স্পট।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো টার্গেট স্পট।<br>
                <strong>কীটনাশকের নাম: </strong>ক্লোরোথালোনিল, মানকোজেব, কপার হাইড্রক্সাইড, প্রোপিকোনাজল।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ক্লোরোথালোনিলগ্রুপের (ব্রাভো,ইকো) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। মানকোজেবগ্রুপের (মানজাতে, ডাইথেন এম-৪৫) প্রতি শতক জমিতে ১৬ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। কপার হাইড্রক্সাইডগ্রুপের (কোসিড, চ্যাম্প) প্রতি শতক জমিতে ১০ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। প্রপিকোনাজলগ্রুপের ছত্রাকনাশক (ক্যাব্রিও) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো মোজাইক ভাইরাস।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো মোজাইক ভাইরাস।<br>
                <strong>কীটনাশকের নাম: </strong>বাইফেনথ্রিন, অ্যাবামেকটিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>বাইফেনথ্রিনগ্রুপের (ব্রিগেড) প্রতি শতক জমিতে ১.২৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। অ্যাবামেকটিনগ্রুপের (আভিদ) প্রতি শতক জমিতে ০.৩২ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //টমেটো হলুদ পাতা কার্ল ভাইরাস।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>টমেটো হলুদ পাতা কার্ল ভাইরাস।<br>
                <strong>কীটনাশকের নাম: </strong>ইমিডাক্লোপ্রিড, থায়ামেথক্সাম, ডিনোটেফুরান।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>ইমিডাক্লোপ্রিডগ্রুপের (এডমায়ার প্রো) প্রতি শতক জমিতে ৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। থায়ামেথক্সামগ্রুপের (ক্রুজার ম্যাক্স) প্রতি শতক জমিতে ৩.৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। ডিনোটেফুরানগ্রুপের (ভেনম) প্রতি শতক জমিতে ২.৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        //দুই দাগযুক্ত স্পাইডার মাইট।
        elseif(trim($vls) =="---") {
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>রোগের নাম: </strong>দুই দাগযুক্ত স্পাইডার মাইট।<br>
                <strong>কীটনাশকের নাম: </strong>অ্যাবামেকটিন, বাইফেনথ্রিন।<br>
                <div class='mt-2 text-center'>
                    <strong>ব্যবহারবিধি: </strong>
                    <p style='text-align: justify;'>অ্যাবামেকটিনগ্রুপের (আভিদ) প্রতি শতক জমিতে ০.৩২ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে। বাইফেনথ্রিনগ্রুপের (ব্রিগেড) প্রতি শতক জমিতে ১.২৫ মিলি. / লি. হারে পানিতে মিশিয়ে ১০ দিন পরপর ৩ বার শেষ বিকেলে স্প্রে করতে হবে।
                </p>
                </div>
            </div>"
        . "</td></tr>";

        }
        else{
            $result .= "<tr><td>" .
            "<div class='px-2'>
                <strong>সুস্থ্য এবং স্বাস্থ্যকর পাতা।</strong><br>
            </div>"
        . "</td></tr>";

        }

    }
    
    $result .= "</table>";

    return $result;
}


// Function to send a GET request to fetch prediction data
function getPredictionData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>কিটনাশক সুপারিশ</title>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php
        include "navbar.php";
    ?>
    <div class="loader"></div>
    <div class="container">
        <div class="text-center" style="max-width:600px; margin: 0 auto" >
            <form action="" method="POST" enctype="multipart/form-data">
                <label class="h3 mt-5 mb-4" for="image">ফসলের রোগাক্রান্ত পাতার ছবি আপলোড করুন:</label>
                <input class="form-control form-control-sm" type="file" name="image" id="image">
                <button class="btn btn-success mt-4 mb-3" type="submit" id="submitButton" name="image_upload">পাতার ছবি আপলোড করুন</button>
            </form>
        </div>
    <div class="text-center">
        <div class="spinner-border text-success d-none m-5" id="spinner" role="status" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only"></span>
        </div>
    </div>

        <div class="text-left" style="max-width:600px; margin: 0 auto" >
            <?php
                if (isset($result)) {
                    echo '<h3 class="mt-4 text-center">পাতার আপলোডকৃত ছবি থেকে প্রদত্ত ফলাফল:</h3>' . $result;
                }
            ?>
        </div>
    </div>

    <?php
        include 'footer.php';
    ?>

    <script>
    document.getElementById('submitButton').addEventListener('click', function() {
        document.getElementById('spinner').classList.remove('d-none');
    });
    </script>
    <script src="main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
