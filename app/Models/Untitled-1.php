        <?php


        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name_ar');                             // Arabic name
            $table->string('name_en')->nullable();                 // English name
            $table->text('description_ar')->nullable();            // Arabic description
            $table->text('description_en')->nullable();            // English description

            $table->string('icon')->nullable();                    // Font Awesome icon class
            $table->string('color')->default('#007bff');         // Category color
            $table->integer('sort_order')->default(0);             // For ordering
            $table->boolean('is_active')->default(true);           // Active status

            $table->string('ctg_key')->nullable()->comment("auto generate");                 // the same as english name without space
            $table->string('level')->nullable()->comment("auto generate");                   // like tawjihi_program_subject || semester || school_sbjects

            $table->json('parents')->nullable()->comment("auto generate - tree of parents");   // tree of parents
            $table->json('childrens')->nullable()->comment("auto generate - tree of childrens"); // tree of childrens

            $table->unsignedBigInteger('parent_id')->nullable()->comment("direct parent");   // Self-referencing for hierarchy
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->enum('type', ['class', 'major'])->comment("not editable")->default('class');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['parent_id', 'is_active', 'sort_order','ctg_key']);
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');                               // Arabic name
            $table->string('name_en')->nullable();                   // English name
            $table->text('description_ar')->nullable();              // Arabic description
            $table->text('description_en')->nullable();              // English description
            $table->string('field_type')->nullable()->comment('require if related to categories.ctg_key=final_year- scientific-fields || literary-fields');
            $table->string('icon')->nullable();                      // Font Awesome icon class
            $table->string('color')->default('#007bff');           // Category color
            $table->integer('sort_order')->default(0);               // For ordering
            $table->boolean('is_active')->default(true);             // Active status

            // direct grade year or semester
            $table->unsignedBigInteger('grade_id')->nullable()->comment('not editable - direct grade year or semester');
            $table->foreign('grade_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('programm_id')->nullable()->comment('not editable - root category id (programm)');   // root category id (programm)
            $table->foreign('programm_id')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['programm_id','grade_id', 'is_active', 'sort_order','field_type']);
        });

        Schema::create('subject_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->boolean('is_optional')->default(false);                    // if optional or non optional subject for this category
            $table->boolean('is_ministry')->default(true);                     // if optional or non optional subject for this category

            $table->unique(['category_id', 'subject_id','is_optional', 'is_ministry']);
            $table->index(['category_id', 'subject_id']);
            $table->timestamps();
        });

        // مبحث اختياري عام لحقل علمي
        //  مطلوب المواد اللي ف سنه التوجيهي ف اي حقل عدا الهندسي ومتكونش مادة الزامية من طرف المدرسة ولا الوزارة علي الشعبة الهندسية او الشعبة اللي هيكون مقصود بيها الكويري لو الكويري مختلف
        Subject::with(['parent_categories'])
        ->wherePivot('is_optional', 1)
        ->whereHas('parent_categories',function($q) {
            $q->where('ctg_key','2008');
            // $q->where('name_en','optional');
        })
        ->whereDoesntHave('parent_category',function($q) {
            $q->where('is_active',0);
            $q->where('ctg_key','engineering_field');
            $q->where('ctg_key','mandatory_subjects');
        })
        ->where('is_active',1);


        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('description_en');
            $table->text('description_ar');
            $table->double('selling_price');
            $table->string('photo');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->timestamps();
        });

        // ضيف المبحث الاختياري كمادة
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->float('price', 15, 3)->unsigned();
            $table->text('description')->nullable();
            $table->string('image', 128)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('how_much_course_can_select')->default(1);
            $table->enum('type', ['class', 'subject'])->default('class');
            $table->timestamps();
        });
        Schema::create('package_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                ->constrained('packages')
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade');


            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onDelete('cascade');

            $table->unique(['package_id', 'category_id']);
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->decimal('total_grade', 8, 2)->default(0.00);
            $table->integer('duration_minutes')->nullable(); // Duration in minutes
            $table->integer('attempts_allowed')->default(1); // Number of attempts allowed
            $table->decimal('passing_grade', 5, 2)->default(50.00); // Minimum grade to pass
            $table->boolean('shuffle_questions')->default(false); // Randomize question order
            $table->boolean('shuffle_options')->default(false); // Randomize option order
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('is_active')->default(true);
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('course_sections')->onDelete('cascade');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('created_by_admin')->nullable();
            $table->foreign('created_by_admin')->references('id')->on('admins')->onDelete('set null');

            $table->timestamps();
        });
