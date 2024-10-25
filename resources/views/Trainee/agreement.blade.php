@extends('layouts.app')

@section('content')
<div class="container">

<style>
        @media print {
            img {
                display: block;
                width: 100px; /* Adjust as needed */
                height: auto;
            }

            /* Optional: Hide the print button during printing */
            .btn {
                display: none;
            }
        }
    </style>

<!-- <form action="{{ route('download.agreement', ['id' => $trainee->id]) }}" method="GET">
    <button type="submit" class="btn btn-primary">Download Document</button>
</form> -->
<!-- Container for the button and photo -->
<div>
    <button onclick="printAgreement()" class="btn btn-primary" style="float: left; margin-top: 1%;">Print Document</button>

    <a href="{{ auth()->check() ? route('trainee.index') : url('/home') }}" class="btn btn-secondary btn-custom" style="float: left; margin-top: 1%;">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
</div>

<div id="agreement-content">
    <!-- Clear float -->
    <h1 style="clear: both; margin-top: 5px; text-align: center;">ሰለሞን የአሽከርካሪዎች ማሰልጠኛ ተቋም</h1>

    <img src="{{ asset('storage/trainee_photos/' . $trainee->photo) }}" alt="Trainee Photo" style="width: 100px; height: 100px; margin-left: 75%;">

    <h1>የእጩ አሽከርካሪ ሰልጣኞች መመዝገቢያ እና የውል ፎርም</h1>
    <p>
        እኔ <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong> 
        በ አዲስ አበባ ክ/ከተማ <strong><u>{{ $trainee->ክፍለ_ከተማ }}</u></strong> 
        የትውልድ ስፍራ <strong><u>{{ $trainee->የትዉልድ_ቦታ }}</u></strong> 
        ወረዳ <strong><u>{{ $trainee->ወረዳ }}</u></strong> 
        የቤት ቁጥር <strong><u>{{ $trainee->house_no }}</u></strong> 
        ስልክ ቁጥር <strong><u>{{ $trainee->phone_no }}</u></strong> 
        ነዋሪ የሆንኩ ስሆን በሰሎሞን የአሽከርካሪዎች ማሠልጠኛ ተቋም ውስጥ 
        በ <strong><u>{{ $trainee->license_type }}</u></strong> 
        ካታጎሪ ስልጠና ለመውሰድ በመወሰኔ በማሠልጠኛ ውስጥ ስሰለጥን ከዚህ በታች የተደነገጉትን ህግጋትና ደንቦች አክብሬና ወድጄ 
        ልፈፅም በዚህ የመመዝገቢያ ፎርም አንብቤና ወስኜ የገባሁ መሆኑን በመጨረሻ ላይ በሚገኘው የመፈረሚያ ቦታ ላይ በፊርማዬ አረጋግጫለሁ፡፡
    </p>
    
    <p><strong>አንቀጽ 1 - የስልጠና መርሓግብር በተመለከተ</strong></p>
    <ul>
    <li>1.1, ማንኛውም እጩ የአሽከርካሪ ስልጣኝ ምዝገባ ካካሄደ በኃላ ማሰልጠኛ ድርጅቱ በሚያወጣው የሥልጠና ፕሮግራም መሰረት ያለምንም ቀሪ ስልጠናውን ሣያስተጎጉል የመከታተል ግዴታ አለበት፡፡</li>
    <li>1.2, በዙሩ በስልጠናው ቀሪ የሆነ ሠልጣኝ በሚቀጥለው ዙር ያመለጠውን ስልጠናም ሆነ ፈተና ወስዶ ማሟላት አለበት ለፈተናም ብቁ ሆኖ ሲገኝ በተቀጠረለት የጊዜ ፕሮግራም መሰረት ፈተናውን ይወስዳል፡፡</li>
    <li>1.3, በሠልጣኙ ምክንያት የፈተና ቀጠሮ ፕሮግራም ቢስተጓጎል ሀላፊነቱ የሰልጣኙ ይሆናል፡፡</li>
</ul>
    <p><strong>አንቀጽ 2 - የስልጠና ጊዜ በተመለከተ</strong></p>
    <p>ማንኛውም እጩ የአሽከርካሪ ሠልጣኝ የክፍል ውስጥ ስልጠናና የተግባር ስልጠናውን በ 3 ወር ጊዜ ውስጥ ማጠናቀቅ ይኖርበታል፡፡ በሰልጣኙ የተለያዩ ምክንያት በዚህ ጊዜ ውስጥ ስልጠናውን ማጠናቀቅ ካልቻለ አሳማኝ ማስረጃ ካቀረቡ ተጨማሪ 3 ወር አጠቃላይ 6 ወር ይሆናል ከማሰልጠኛችን ጋር የገባው የስልጠና ውል ይቋረጣል። እንዳስፈላጊነቱ እንደ አዲስ ሠልጣኝ አስፈላጊውን ፎርም በማሟላት ሥልጠናውን እንደ አዲስ መከታተል ይችላል፡፡</p>
    <p><strong>አንቀጽ 3- ዲሢፒሊን በተመለከተ</strong></p>
    <ul>
        <li>ማንኛውም እጩ የአሽከርካሪ ሰልጣኝ ስልጠናውን ዲሲፕሊን በተሞላበት አግባብ በወጣው ፕሮግራም መሰረት መሰልጠን አለበት</li>
        <li>በስልጠና ወቅት አሰልጣኝንም ሆነ የተቁሙን ሰራተኞችመሳደብ፣ማንጉአጠጥ፣ማስፈራራት፣ባልተገባ ጥቅም ለመደለል መሞከር ና ሌሎች አላስፈላጊ የሆኑ ባህሪያት በሚያሣዩ ዕጩ ሰልጣኞች ላይ ያለምንም ቅድመ ሁኔታ ከስልጠናው እንዲሰናበቱ ይደረጋል</li>
    </ul>
    <p><strong>አንቀጽ 4 - አዋጅና መመሪያ በተመለከተ</strong></p>
    <ul>
        <li>ማንኛውም ሰልጣኝ የሚሰለጥነው በአዲስ መልክ ተሻሽሎ በፀደቀው አዋጅ 1074/2010 መሆኑን አውቆ እና ፈቅዶ ነው፡፡</li>
    </ul>
    <p><strong>አንቀጽ 5- የስልጠና መስፈርቶች.</strong></p>
    <ul>
        <li>5.1, የትምህርት ማስረጃ</li>
        <ul>
            <li>ለአውቶሞቢል - 4ኛ ክፍልና ከዚያ በላይ</li>
            <li>ለህዝብ1፡ - 10ኛ ክፍልና ከዚያ በላይ የሆነ 2 ኮፒ ማቅረብ ይጠበቅባቸዋል</li>
        </ul>
        <li>5.2, መታወቂያ</li>
        <ul>
            <li>ማንኛውም እጩ የአሽከርካሪ ስልጣኝ ለምዝገባ ሲመጣ -የታደሰ የቀበሌ መታወቂያ ወይም ኢትዮጵያዊነቱን የሚገልጽ ፓስፖርት ወይም የልደት ሰርተፊኬት 2ኮፒ ማቅረብ ይገባዋል</li>
        </ul>
        <li>5.3, ዕድሜ</li>
        <ul>
            <li>ለአውቶሞቢል - 18ዓመትና ከዚያ በላይ</li>
            <li>ለህዝብ1፡ ደረቅ - 22 ዓመትና ከዚያበላይ</li>
        </ul>
        <li>5.4, ፎቶግራፍ- 7 ፓስፖርት ሳይዝ ጉርድ ፎቶ</li>
        <li>5.5, የጤና ምርመራ ፤-ማንኛውም ሰልጣኝ በአዲስ አበባ ከተማ አስተዳደር ውስጥ ከሚገኙ የመንግስት የመንግስት ጤና ድርጂቶች ወይም ከመንገድ ትራንስፖርት ፍቃድ ካላቸው የግል ጤና ድርጅቶች ለማሽከርከር ብቁ መሆናቸው የሚገልጽ የጤና ምርመራ ማቅረብ አለባቸው</li>
    </ul>
    <p><strong>አንቀጽ - 6- የክፍያ ሁኔታ</strong></p>
    <ul>
        <li>ሰልጣኞች ክፍያቸው በ2 ዙር መክፈል ይችላሉ ፡፡ ሰልጣኙ ግማሽ ስልጠና ከወሰደ በሁዋላ 2ኛውን ዙር መክፈል አለበት፡፡ ነገር ግን ከግማሽ ስልጠና በሁዋላ ባሉት 10 ቀናት ካልከፈለ ውሉ ይሰረዛል ፡፡</li>
    </ul>
    <p>አመልካች እኔ አቶ/ወ/ሮ/ወሪት <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong> የተባልኩ ሰልጣኝ ከዚህ በላይ የተዘረዘሩት ውሎች እና መስፈርቶ አሟልቸ ፡ አምኘበት እና አክብሬ ልሰለጥን የተስማማሁ መሆኔ ከዚህ በታች ባለው ፊርማዬ አረጋግጣለሁ ፡፡</p>
    <p> የዕጩ  ስልጣኝ  ሙሉ ስም <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong> ፊርማ-------------ቀን-------------- </p>



    <p> ሥልጠናውን ለመከታተል ለሚፈልጉና መስፈርቱን ለሚያሟሉ ማንኛውም ሰልጣኝ ከዚህ በታች የተገለፁትን ማሟላት ይጠበቅባቸዋል፡፡</p>
    <h4 style= "text-align:center;">የትምህርት ማስረጃ</h4>
<ul>                                   
    <li>በደረቅ-1 ከ10ኛ ክፍል በላይ የትምህርት ማስረጃ ማቅረብ አለበት፡፤</li>
    <li>የአውቶሞቢል ተማሪ ወይም ተመዝጋቢ ከሆኑ ከአራተኛ ክፍል በላይ የትምህርት ማስረጃ የማቅረብ ግዴታ አለበት።</li>
    <li>የታክሲ - 2 ፣ የሕዝብ- 1 ተመዝጋቢ ከሆኑ 10ኛ ክፍል ያጠናቀቀበትን እና ከዚያ በላይ የትምህርት ማስረጃ የማቅረብ ግዴታ አለበት።</li>
</ul>
<h5 style="margin-left:140px">እድሜ</h5>
<ul>
    <li>ለአውቶሞቢል ከ18 ዓመት ጀምሮ  </li>
    <li>ለደረቅ-1 እና ህዝብ-1 ከ22ዓመት ጀምሮ</li>
    <li>ለደረቅ-2 እና ህዝብ-2 ከ24ዓመት ጀምሮ</li>
    <li>ለደረቅ-3 እና ህዝብ-3 ከ26ዓመት ጀምሮ</li>
</ul>
<p>ፎቶግራፍ ለሁሉም ሠልጣኞች  ሶስት በአራት ስድስት ጉርድ ፎቶ ግራፍ (3x4=6)</p>
<li>የጤና ምርመራ በአቅራቢያዎ ከሚገኝ የመንግስት ጤና ጣቢያ ወይም ፍቃድ ከተሰጠው የግል ሆስፒታል ተመርምረው መረጃውን ኦርጅናልና 2 ኮፒ ማቅረብ ይጠበቅቦታል፡፡</li>
<li>ለሁሉም የተቋሙ ሰልጣኞች የትምህርት ማስረጃ 2 ኮፒ ማንኛውም የቀበሌ መታወቂያ 2-ኮፒ፤የልደት ሰርተፍኬት2-ኮፒ ወይም ኢትዮጵያዊነቱን የሚገልጽ ፓስፖርት ማቅረብ አለበት።</li>
<p>
    በመሆኑም እኔ እጩ ሰልጣኝ <p> ከዚህ በላይ የተዘረዘሩትን ህጋዊ ማስረጃዎቼን ከዚሁ ማመልከቻ ጋር አያይዤ ያቀረብኩ መሆኔንና  
    እድሜያም መሰልጠን በፈለኩበት ካታጎሪ የተቀመጠውን መስፈርት የማሟላ መሆኔን በዚህ ማመልከቻ አረጋግጣለሁ ። በመሆኑም ባጠቃላይ ስለ ስለጠናውም ሆነ ስለ እሚያስፈልገው መስፈርት ከተgሙ ኢንፎርሜሽን ዴስክ 
    ማወቅ የሚገባኝን ሁሉ በሚገባ ተረድቼ  ያወኩ በመሆኑና ለመሰልጠን የወሰንኩ በመሆኑ አስፈላጊውን ሂሳብ ከፍዬ ህጋዊ ደረሰኝ እንዲሰጠኝ እየጠየኩ ደረሰኝ ካስቖረጥኩ በኁላ  
    የካታጎሪ ለውጥ ጥያቄም ሆነ የገንዘብ ይመለስልኝ ጥያቄ ማቅረብ እንደሌለብኝ አምኜ ስልጠናውን ለመውሰድ በፊርማዬ አረጋግጬ ወስኛለሁ። 
</p>
			
<h4 style= "text-align:center;">የክፍያ ሁኔታ</h4>
<p>ለ አውቶሞቢል ና ለ ደረቅ-1 እና ለ ህዝብ -1 ሰልጣኞች ክፍያ በአንድ ጊዜ ወይም በሁለት ጊዜ ተከፍሎ ይጠናቀቃል፡፡ </p>                                                                                                 
<li>ማንኛውም የአሽከርካሪነት ስልጠና ተከታታይ ተማሪ በምድቡ የተፈቀደለትን ስዓት በሚገባ ተጠቅሞና በተገቢው የቀጠሮ ምደባ ጊዜ የመንገድ ትራንስፖርት የሚሰጠውን አጠቃላይ የመመዘኛ ፈተና ሊያልፍ 
    ካልቻለ ወደ ማጠናከሪያ ክፍል ገብቶ ለመማር ይገደዳል በዚህም ታግዞ ማለፍ ካልቻለ ወደ ሙሉ ስልጠና ክፍል ገብቶ ለመማር ይገደዳል፡፡</li>
<li>የተግባር ስልጠና ፈተና ካላለፈ ለ 5 ስዓት የተግባር ስልጠና እንዲወስድ ይደረጋል፡፡ </li>

<h4 style= "text-align:center;">ማሳሰቢያ</h4>
<p>ማንኛውም የአሽከርካሪነት ስልጠና ወሳጅ በማሠልጠኛ ተቋሙ ፕሮግራም መሠረት የፈተና ቀጠሮ ተቀጥሮለት በቀጠሮ ጊዜ ባለመገኘቱ 
    ምክንያት ፈተና ቢያመልጠው ተቋማችንን የማይመለከት መሆኑን እንዲረዱልን በትህትና እንገልፃለን፡፡</p>
<p>እንዲሁም በመንገድ ትራንስፖርት መሥሪያ ቤት በኩል በፈተና ጊዜ ለሚገጥመው ችግር ማለትም</p>
<ul>
    <li>የመብራት መቋረጥ</li>
    <li>የካሜራ ብልሽት </li>
    <li>የባዕላት መኖር </li>
</ul>
<p>እና ሌሎች በማናውቃቸው ምክንያቶች የፈተና ሽግሽግ በሚኖርበት ጊዜ የተቋማችን አለመሆኑን እንዲረዱልን እና በትዕግስት ሁኔታዎች እስኪስተካከሉ እንዲጠብቁ  በአክብሮት እንገልጻለን፡፡</p>
<p> 
    አመልካች እኔ <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong> ከላይ የተገለፀውን ሁሉ አውቄ ተረድቼና 
    ተስማምቼ ወስኜ የተመዘገብኩ መሆኑን በተለመደው ፊርማዬ አረጋግጣለው፡፡
</p>     
<p>እኔ ሰልጣኝ ለተቋሙ የሰጠውት ማንኛውም የትምህርት ሆነ የቀበሌ መታወቂያ ማስረጃ ትክክል መሆኑንም ከታች ባለው በፊርማዬ አረጋግጣለሁ፡፡</p>                                                                                     
<p>የእጩ ሰልጣኝ ሙሉ ስም <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong>  ፊርማ ______________ ቀን ______________</p>                                                        

<h4 style= "text-align:center;">የእጩ አሽከርካሪ ሰልጣኞች መመዝገቢያ ፎርም</h4>
<p>
    እኔ <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong>
    በ አዲስ አበባ ክ/ከተማ <strong><u>{{ $trainee->ክፍለ_ከተማ }}</u></strong> 
    ወረዳ <strong><u>{{ $trainee->ወረዳ }}</u></strong> 
    የቤት ቁጥር <strong><u>{{ $trainee->house_no }}</u></strong> 
    ስልክ ቁጥር <strong><u>{{ $trainee->phone_no }}</u></strong> 
    ነዋሪ የሆንኩ ስሆን ሰሎሞን የአሽከርካሪዎች ማሠልጠኛ ተቋም ውስጥ  
    በ <strong><u>{{ $trainee->license_type }}</u></strong> 
    ካታጎሪ ስልጠና ለመውሰድ በመወሰኔ በማሠልጠኛ ውስጥ ስሰለጥን ከዚህ በታች የተደነገጉትን ህግጋትና ደንቦች አክብሬና ወድጄ ልፈፅም 
    በዚህ የመመዝገቢያ ፎርም አንብቤና ወስኜ የገባሁ መሆኑን በመጨረሻ ላይ በሚገኘው የመፈረሚያ ቦታ ላይ በፊርማዬ አረጋግጫለሁ፡፡
</p>
<ol>
    <li>	ማንኛውም እጩ የአሽከርካሪ ስልጣኝ ምዝገባ ካካሄደ በኃላ ማሰልጠኛ ድርጅቱ በሚያወጣው የሥልጠና ፕሮግራም መሰረት ያለምንም ቀሪ ስልጠናውን ሣያስተጎጉል የመከታተል ግዴታ አለበት በዙሩ በስልጠናው ቀሪ የሆነ ሠልጣኝ በሚቀጥለው ዙር ያመለጠውን ስልጠናም ሆነ ፈተና ወስዶ ማሟላት አለበት ለፈተናም ብቁ ሆኖ ሲገኝ በተቀጠረለት የጊዜ ፕሮግራም መሰረት ፈተናውን ይወስዳል፡፡ ሆኖም በሠልጣኙ ምክንያት የፈተና ቀጠሮ ፕሮግራም ቢስተጓጎል ሀላፊነቱ የሰልጣኙ ይሆናል፡፡</li>
    <li>	 ማንኛውም እጩ የአሽከርካሪ ሠልጣኝ የክፍል ውስጥ ስልጠናና የተግባር ስልጠናውን በ9ወር ቀን ጊዜ ውስጥ ማጠናቀቅ ይኖርበታል፡፡ በሰልጣኙ የተለያዩ ምክንያት በዚህ ጊዜ ውስጥ ስልጠናውን ማጠናቀቅ ካልቻለ ከማሰልጠኛችን ጋር የገባው የስልጠና ውል ይቋረጣል። እንዳስፈላጊነቱ እንደ አዲስ ሠልጣኝ አስፈላጊውን ፎርማሊቲ በማሟላት ሥልጠናውን እንደ አዲስ መከታተል ይችላል፡፡  </li>                                     
    <li>	ማንኛውም እጩ የአሽከርካሪ ሰልጣኝ ስልጠናውን ዲሲፕሊን በተሞላበት አግባብ በፕሮግራሙ መሰረት መሰልጠን አለበት ሆኖም በስልጠና ወቅት አሰልጣኝንም ሆነ የተቁሙን ሰራተኞች መሳደብ፣ማንጉአጠጥ፣ማስፈራራት፣ባልተገባ ጥቅም ለመደለል መሞከር ና ሌሎች አላስፈላጊ የሆኑ ባህሪያት  በሚያሣዩ  ዕጩ  ሰልጣኞች  ላይ  ያለምንም  ቅድመ  ሁኔታ  ከስልጠናው  እንዲሰናበቱ  ይደረጋል</li>
    <li>	ማንኛውም ሰልጣኝ የሚሰለጥነው በአዲስ መልክ ተሻሽሎ በፀደቊ አዋጅ 1074/2010 መሆኑን አውቆ እና ፈቅዶ ነው፡፡</li>
    <li> ሥልጠናውን ለመከታተል ለሚፈልጉና መስፈርቱን ለሚያሟሉ ማንኛውም ሰልጣኝ ከዚህ በታች የተገለፁትን ማሟላት ይጠበቅባቸዋል፡፡</li>
</ol>

<h4 style= "text-align:center;">የትምህርት ማስረጃ</h4>
<ul>                                   
    <li>በደረቅ-1 ከ10ኛ ክፍል በላይ የትምህርት ማስረጃ ማቅረብ አለበት፡፤</li>
    <li>የአውቶሞቢል ተማሪ ወይም ተመዝጋቢ ከሆኑ ከአራተኛ ክፍል በላይ የትምህርት ማስረጃ የማቅረብ ግዴታ አለበት።</li>
    <li>የታክሲ - 2 ፣ የሕዝብ- 1 ተመዝጋቢ ከሆኑ 10ኛ ክፍል ያጠናቀቀበትን እና ከዚያ በላይ የትምህርት ማስረጃ የማቅረብ ግዴታ አለበት።</li>
</ul>

<h5 style="margin-left:140px">እድሜ</h5>
<ul>
    <li>ለአውቶሞቢል ከ18 ዓመት ጀምሮ  </li>
    <li>ለደረቅ-1 እና ህዝብ-1 ከ22ዓመት ጀምሮ</li>
    <li>ለደረቅ-2 እና ህዝብ-2 ከ24ዓመት ጀምሮ</li>
    <li>ለደረቅ-3 እና ህዝብ-3 ከ26ዓመት ጀምሮ</li>
</ul>

<p>ፎቶግራፍ ለሁሉም ሠልጣኞች  ሶስት በአራት ስድስት ጉርድ ፎቶ ግራፍ (3x4=6)</p>
<li>የጤና ምርመራ በአቅራቢያዎ ከሚገኝ የመንግስት ጤና ጣቢያ ወይም ፍቃድ ከተሰጠው የግል ሆስፒታል ተመርምረው መረጃውን ኦርጅናልና 2 ኮፒ ማቅረብ ይጠበቅቦታል፡፡</li>
<li>ለሁሉም የተቋሙ ሰልጣኞች የትምህርት ማስረጃ 2 ኮፒ ማንኛውም የቀበሌ መታወቂያ 2-ኮፒ፤የልደት ሰርተፍኬት2-ኮፒ ወይም ኢትዮጵያዊነቱን የሚገልጽ ፓስፖርት ማቅረብ አለበት።</li>
<p>በመሆኑም እኔ እጩ ሰልጣኝ <p> ከዚህ በላይ የተዘረዘሩትን ህጋዊ ማስረጃዎቼን ከዚሁ ማመልከቻ ጋር አያይዤ ያቀረብኩ መሆኔንና  
    እድሜያም መሰልጠን በፈለኩበት ካታጎሪ የተቀመጠውን መስፈርት የማሟላ መሆኔን በዚህ ማመልከቻ አረጋግጣለሁ ። በመሆኑም ባጠቃላይ ስለ ስለጠናውም ሆነ ስለ እሚያስፈልገው መስፈርት ከተgሙ ኢንፎርሜሽን ዴስክ 
    ማወቅ የሚገባኝን ሁሉ በሚገባ ተረድቼ  ያወኩ በመሆኑና ለመሰልጠን የወሰንኩ በመሆኑ አስፈላጊውን ሂሳብ ከፍዬ ህጋዊ ደረሰኝ እንዲሰጠኝ እየጠየኩ ደረሰኝ ካስቖረጥኩ በኁላ  
    የካታጎሪ ለውጥ ጥያቄም ሆነ የገንዘብ ይመለስልኝ ጥያቄ ማቅረብ እንደሌለብኝ አምኜ ስልጠናውን ለመውሰድ በፊርማዬ አረጋግጬ ወስኛለሁ። </p>
			
<h4 style= "text-align:center;">የክፍያ ሁኔታ</h4>
<p>ለ አውቶሞቢል ና ለ ደረቅ-1 እና ለ ህዝብ -1 ሰልጣኞች ክፍያ በአንድ ጊዜ ወይም በሁለት ጊዜ ተከፍሎ ይጠናቀቃል፡፡ </p>                                                                                                 
<li>ማንኛውም የአሽከርካሪነት ስልጠና ተከታታይ ተማሪ በምድቡ የተፈቀደለትን ስዓት በሚገባ ተጠቅሞና በተገቢው የቀጠሮ ምደባ ጊዜ የመንገድ ትራንስፖርት የሚሰጠውን አጠቃላይ የመመዘኛ ፈተና ሊያልፍ 
    ካልቻለ ወደ ማጠናከሪያ ክፍል ገብቶ ለመማር ይገደዳል በዚህም ታግዞ ማለፍ ካልቻለ ወደ ሙሉ ስልጠና ክፍል ገብቶ ለመማር ይገደዳል፡፡</li>
<li>የተግባር ስልጠና ፈተና ካላለፈ ለ 5 ስዓት የተግባር ስልጠና እንዲወስድ ይደረጋል፡፡ </li>

<h4 style= "text-align:center;">ማሳሰቢያ</h4>
<p>ማንኛውም የአሽከርካሪነት ስልጠና ወሳጅ በማሠልጠኛ ተቋሙ ፕሮግራም መሠረት የፈተና ቀጠሮ ተቀጥሮለት በቀጠሮ ጊዜ ባለመገኘቱ 
    ምክንያት ፈተና ቢያመልጠው ተቋማችንን የማይመለከት መሆኑን እንዲረዱልን በትህትና እንገልፃለን፡፡</p>
<p>እንዲሁም በመንገድ ትራንስፖርት መሥሪያ ቤት በኩል በፈተና ጊዜ ለሚገጥመው ችግር ማለትም</p>
<ul>
    <li>የመብራት መቋረጥ</li>
    <li>የካሜራ ብልሽት </li>
    <li>የባዕላት መኖር </li>
</ul>
<p>እና ሌሎች በማናውቃቸው ምክንያቶች የፈተና ሽግሽግ በሚኖርበት ጊዜ የተቋማችን አለመሆኑን እንዲረዱልን እና በትዕግስት ሁኔታዎች እስኪስተካከሉ እንዲጠብቁ  በአክብሮት እንገልጻለን፡፡</p>
<p>አመልካች እኔ <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong> ከላይ የተገለፀውን ሁሉ አውቄ ተረድቼና 
ተስማምቼ ወስኜ የተመዘገብኩ መሆኑን በተለመደው ፊርማዬ አረጋግጣለው፡፡</p>     
<p>እኔ ሰልጣኝ ለተቋሙ የሰጠውት ማንኛውም የትምህርት ሆነ የቀበሌ መታወቂያ ማስረጃ ትክክል መሆኑንም ከታች ባለው በፊርማዬ አረጋግጣለሁ፡፡</p>                                                                                     
<p>የእጩ ሰልጣኝ ሙሉ ስም <strong><u>{{ $trainee->ሙሉ_ስም }}</u></strong>  ፊርማ ______________ ቀን ______________</p>                                                        

</div>  
</div>
<script>
function printAgreement() {
    const printWindow = window.open('', '', 'height=1200,width=1200');
    printWindow.document.write('<html><head><title>Print Agreement</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(document.getElementById('agreement-content').innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection