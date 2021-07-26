    <div class="row" >
        <div class="col-6 col-sm-12 col-md-12 col-xl-6">
            <div class="col-inner DashBoardcolumn">

                                
            <div class="dashSection shadow-green">
                    <h2>Last comments</h2>
                    <?php if(isset($lastComments) && $lastComments && count($lastComments) > 0 ): ?>
                        <div class="columnList">
                            <?php foreach($lastComments as $comment): ?>
                                <div class="comment" style="width: 100%">
                                    <p><?= $comment['message'] ?></p>
                                    <div class="rowList" style="justify-self: flex-end;">
                                        <p style="font-weight: bold;"><?= $comment['author']?></p>
                                        <a href="<?= \App\Core\Helpers::renderCMSLink("ent/post?id=", $this->site)?><?= $comment['idPost']?>#<?= $comment['id']?>"><button class="cta-green-light">Post</button></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No comment yet</p>
                    <?php endif; ?>

                </div>

                <div class="dashSection shadow-green">
                    <h2>Menus</h2>
                        <?php if(isset($menus) && $menus && count($menus) > 0 ): ?>
                            <div class="rowList">
                                <?php foreach($menus as $menu): ?>
                                    <div class="col-4" style="padding:0.5em"> 
                                        <div class="menu" style="border: 1px solid #9df5d9;">
                                            <h1><?= $menu['name'] ?></h1>
                                            <p><?= $menu['description'] ?></p>
                                            <div class="btn-row">
                                                <a target="_blank" href="<?= App\Core\Helpers::renderCMSLink('admin/menus/export?id='.$menu['id'], $this->site) ?>" class="btn-menu btn-menu-purple">Export (HTML)</a>
                                                <a href="<?= App\Core\Helpers::renderCMSLink('admin/menus/edit?id='.$menu['id'], $this->site)  ?>" class="btn-menu">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="row" style="width:100%; display:flex; justify-content:center;">
                                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M48.6239 11.9674C47.5756 11.7255 46.5296 12.3778 46.287 13.4261C46.0444 14.4744 46.6982 15.5211 47.7465 15.7637C52.5927 16.8837 57.0223 19.3442 60.5555 22.8774C70.9429 33.2648 70.9429 50.1677 60.5555 60.5552C50.1681 70.9433 33.2652 70.9433 22.8778 60.5552C12.4896 50.1677 12.4896 33.2648 22.8778 22.8774C25.8601 19.8951 29.3894 17.7199 33.3682 16.4107C34.3897 16.075 34.9459 14.9741 34.6102 13.9518C34.2738 12.9294 33.1721 12.3732 32.1505 12.7097C27.5866 14.2104 23.54 16.7045 20.1228 20.1224C8.21558 32.0296 8.21558 51.4037 20.1228 63.3109C26.076 69.2641 33.8961 72.2411 41.717 72.2411C49.5371 72.2403 57.3573 69.2641 63.3113 63.3109C75.2177 51.4037 75.2177 32.0296 63.3113 20.1224C59.2608 16.0727 54.1827 13.2522 48.6239 11.9674V11.9674Z" fill="black"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M97.3465 84.5131L76.9302 64.0968C87.3993 47.6799 85.1067 26.1116 71.2143 12.2185C63.3354 4.3396 52.8595 0 41.7168 0C30.5733 0 20.0974 4.3396 12.2185 12.2185C4.33884 20.0974 0 30.5733 0 41.7168C0 52.8595 4.33884 63.3354 12.2185 71.2143C20.0974 79.0932 30.5725 83.432 41.7152 83.432C49.7124 83.432 57.4158 81.189 64.0968 76.9302L84.5131 97.3465C86.2213 99.0555 88.5002 99.9962 90.9302 99.9962C93.3594 99.9962 95.6383 99.0555 97.3473 97.3465C100.884 93.808 100.884 88.0516 97.3465 84.5131V84.5131ZM94.5915 94.5915C93.6188 95.5635 92.3187 96.0991 90.9302 96.0991C89.5409 96.0991 88.2408 95.5635 87.2681 94.5915L65.7242 73.0476C65.3473 72.67 64.8483 72.477 64.3463 72.477C63.9603 72.477 63.5727 72.5906 63.2362 72.8241C56.9061 77.2148 49.4637 79.5357 41.7152 79.5357C31.6132 79.5357 22.1161 75.602 14.9735 68.4593C0.227356 53.7132 0.227356 29.7195 14.9735 14.9734C22.1169 7.83005 31.6139 3.89633 41.7168 3.89633C51.8189 3.89633 61.3159 7.83005 68.4593 14.9734C81.4049 27.9198 83.2413 48.217 72.8241 63.2362C72.287 64.0106 72.3808 65.0574 73.0476 65.7242L94.5915 87.2681C96.6103 89.2868 96.6103 92.572 94.5915 94.5915V94.5915Z" fill="black"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M53.4774 29.9553C52.7168 29.1946 51.4839 29.1946 50.7225 29.9553L41.7167 38.961L32.7102 29.9553C31.9496 29.1946 30.7159 29.1946 29.9553 29.9553C29.1946 30.7159 29.1946 31.9496 29.9553 32.7102L38.961 41.7167L29.9553 50.7225C29.1946 51.4831 29.1946 52.7168 29.9553 53.4774C30.3352 53.8581 30.8342 54.0481 31.3324 54.0481C31.8313 54.0481 32.3295 53.8581 32.7102 53.4774L41.716 44.4717L50.7217 53.4774C51.1024 53.8581 51.6014 54.0481 52.0996 54.0481C52.5985 54.0481 53.0967 53.8581 53.4774 53.4774C54.2381 52.7168 54.2381 51.4831 53.4774 50.7225L44.4709 41.7167L53.4774 32.7102C54.2381 31.9496 54.2381 30.7159 53.4774 29.9553V29.9553Z" fill="black"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M40.4251 15.1034C41.4978 15.1034 42.3729 14.2283 42.3729 13.1548C42.3729 12.0821 41.4978 11.207 40.4251 11.207C39.3517 11.207 38.4766 12.0821 38.4766 13.1548C38.4766 14.2283 39.3517 15.1034 40.4251 15.1034Z" fill="black"/>
                                </svg>
                            </div>
                    <?php endif; ?>
                </div>

                <div class="dashSection shadow-green">
                    <h2>Data</h2>
                    <div class="rowList">
                        <div class="columnList stats">
                            <svg width="50" height="50" viewBox="0 0 362 361" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M77 179.576V284H181.424L159.647 262.222H98.7778V201.353L77 179.576Z" fill="#2DC091"/>
                                <path d="M358.696 341.602L327.445 309.915V338.444H22.5557V293.255H44.3334V275.833H22.5557V203.422H44.3334V186H22.5557V116.311H44.3334V98.8888H22.5557V38.0199L196.125 210.609V179.902L19.3979 4.04654C17.8749 2.51089 15.929 1.46359 13.8084 1.03817C11.6878 0.612743 9.4886 0.828467 7.4911 1.65784C5.4936 2.48721 3.78833 3.89265 2.59272 5.69498C1.39711 7.4973 0.765316 9.61485 0.777894 11.7777V349.333C0.777894 352.221 1.92511 354.991 3.96718 357.033C6.00924 359.075 8.77887 360.222 11.6668 360.222H350.965C353.127 360.235 355.245 359.603 357.047 358.407C358.85 357.212 360.255 355.506 361.084 353.509C361.914 351.511 362.129 349.312 361.704 347.192C361.279 345.071 360.231 343.125 358.696 341.602V341.602Z" fill="#9E2DC0"/>
                                <path d="M240.333 316.667H283.889C289.665 316.667 295.204 314.372 299.288 310.288C303.372 306.204 305.667 300.665 305.667 294.889V84.7335L280.622 38.6735C278.685 35.3177 275.888 32.5395 272.519 30.6254C269.15 28.7113 265.332 27.7306 261.458 27.7846C257.496 27.816 253.617 28.9277 250.24 31C246.863 33.0722 244.116 36.0265 242.293 39.5446L218.556 84.9512V294.889C218.556 300.665 220.85 306.204 224.934 310.288C229.018 314.372 234.557 316.667 240.333 316.667ZM240.333 90.1779L261.458 49.1268L283.889 90.2868V251.333H240.333V90.1779ZM240.333 269.082H283.889V295.651H240.333V269.082Z" fill="#2DC091"/>
                            </svg>
                                          
                            <span class="greenContent"><?= $datas['theme'] ?></span>
                            <h3>Current design</h3>
                        </div>

                        <div class="columnList stats">
                            <svg width="50" height="50" viewBox="0 0 231 289" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M173 87H58V58.25H173V87ZM173 115.75H58V144.5H173V115.75ZM230.5 29.5V202L144.25 288.25H29.25C21.625 288.25 14.3123 285.221 8.92068 279.829C3.52901 274.438 0.5 267.125 0.5 259.5V29.5C0.5 21.875 3.52901 14.5623 8.92068 9.17068C14.3123 3.77901 21.625 0.75 29.25 0.75H201.75C209.375 0.75 216.688 3.77901 222.079 9.17068C227.471 14.5623 230.5 21.875 230.5 29.5ZM201.75 173.25V29.5H29.25V259.5H115.5V202C115.5 194.375 118.529 187.062 123.921 181.671C129.312 176.279 136.625 173.25 144.25 173.25H201.75Z" fill="#9E2DC0"/>
                            </svg>
                            <span class="greenContent"><?= $datas['pages'] ?></span>
                            <h3>Pages</h3>
                        </div>

                        <div class="columnList stats">
                            <svg width="50" height="50" viewBox="0 0 363 363" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M40.625 0.375H0.375V362.625H362.625V0.375H40.625ZM322.375 40.625V322.375H40.625V40.625H322.375ZM282.125 80.875H80.875V121.125H282.125V80.875ZM80.875 161.375H282.125V201.625H80.875V161.375ZM221.75 241.875H80.875V282.125H221.75V241.875Z" fill="#2DC091"/>
                            </svg>
                            <span class="greenContent"><?= $datas['posts'] ?></span>
                            <h3>Posts</h3>
                        </div>

                        <div class="columnList stats">
                            <svg width="50" height="50" viewBox="0 0 1047 1047" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M941.833 0.530731H105.166C47.4888 0.530731 0.58313 47.4364 0.58313 105.114V1046.36L209.75 837.197H941.833C999.511 837.197 1046.42 790.292 1046.42 732.614V105.114C1046.42 47.4364 999.511 0.530731 941.833 0.530731V0.530731ZM628.083 575.739H262.041V471.156H628.083V575.739ZM784.958 366.572H262.041V261.989H784.958V366.572Z" fill="#2DC091"/>
                            </svg>

                            <span class="greenContent"><?= $datas['comments'] ?></span>
                            <h3>Comments</h3>
                        </div>

                    </div>
                    
                </div>

            </div>
        </div>

        <div class="col-6 col-sm-12 col-md-12 col-xl-6">
            <div class="col-inner DashBoardcolumn">
                <div class="dashSection shadow-purple">
                    <div class="rowList">
                        <h2>Reservations pending validation</h2>
                        <!--<button class="cta-purple">Ajouter</button>-->
                    </div>
                    <?php if(isset($pendingBooking) && count($pendingBooking) > 0 ): ?>
                        <div class="columnList">
                            <div class="array">
                                <h3>Client</h3>
                                <h3>Date</h3>
                                <h3>Hour</h3>
                                <h3>Number</h3>
                                <h3>Plus</h3>
                            </div>
                            <?php foreach($pendingBooking as $booking): ?>
                                <div class="array">
                                    <span><?= $booking['client'] ?></span>
                                    <span><?= $booking['date'] ?></span>
                                    <span><?= $booking['hour'] ?></span>
                                    <span><?= $booking['number'] ?> persons</span>
                                    <span><a href="<?= \App\Core\Helpers::renderCMSLink("admin/booking", $this->site)?>"><button class="cta-green-light">See</button></a></span>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php else: ?>
                        <p>No reservation yet </p>
                    <?php endif; ?>

                </div>
            </div>

            <div class="col-inner">
                <div class="dashSection shadow-purple">
                    <div class="rowList">
                        <h2>Current reservations</h2>
                    </div>
                    <?php if(isset($currentBooking) && count($currentBooking) > 0 ): ?>
                        <div class="columnList">
                            <div class="array">
                                <h3>Client</h3>
                                <h3>Date</h3>
                                <h3>Hour</h3>
                                <h3>Number</h3>
                                <h3>Plus</h3>
                            </div>
                            <?php foreach($currentBooking as $booking): ?>
                                <div class="array">
                                    <span><?= $booking['client'] ?></span>
                                    <span><?= $booking['date'] ?></span>
                                    <span><?= $booking['hour'] ?></span>
                                    <span><?= $booking['number'] ?> persons</span>
                                    <span><a href="<?= \App\Core\Helpers::renderCMSLink("admin/booking", $this->site)?>"><button class="cta-green-light">See</button></a></span>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php else: ?>
                        <p>No reservation yet </p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
