import re

try:
    with open("f:/APLICATIVOS LARAGON/mergulhOS/application/views/tema/menu.php", "r", encoding="utf-8") as f:
        menu = f.read()

    style_match = re.search(r'(<style>.*?</style>)', menu, re.DOTALL)
    if style_match:
        style_content = style_match.group(1)
    else:
        print("Style not found")
        exit(1)

    with open("f:/APLICATIVOS LARAGON/mergulhOS/application/views/conecte/template.php", "r", encoding="utf-8") as f:
        template = f.read()

    # remove old CSS
    template = re.sub(r'#sidebar\s*{(.*?)}', '', template, flags=re.DOTALL)
    
    # ensure no duplicate style insertions
    if 'id="sidebar"' in style_content and 'hide-sidebar' in style_content:
        # insert style before </head> if not already inserted
        if '/* Fix para Tablet/Zoom' not in template:
            template = template.replace("</head>", "\n" + style_content + "\n</head>")

    # fix html toggle menu replacement
    old_nav_str = '<nav id="sidebar">'
    new_nav_str = \
'''<a href="#" class="toggle-menu">
    <div class="mode">
        <div class="moon-menu">
            <i class='bx bx-chevron-right iconX open-2'></i>
            <i class='bx bx-chevron-left iconX close-2'></i>
        </div>
    </div>
</a>
<div class="menu-overlay"></div>
<nav id="sidebar">'''

    if '<a href="#" class="toggle-menu">' not in template:
        template = template.replace(old_nav_str, new_nav_str)
        
    # remove the old visible-phone
    template = re.sub(r'<a href="#" class="visible-phone">.*?</a>', '', template, flags=re.DOTALL)

    # fix user nav text overflow
    old_user_nav = """<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='bx bx-user-circle iconN1'></i>
                        <?= $this->session->userdata('nome') ?> </a>"""
    
    if old_user_nav in template:
        new_user_nav = """<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display:flex; align-items:center; gap:5px;"><i class='bx bx-user-circle iconN1' style="font-size: 24px;"></i><span class="text" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block;"> <?= $this->session->userdata('nome') ?> </span></a>"""
        template = template.replace(old_user_nav, new_user_nav)

    with open("f:/APLICATIVOS LARAGON/mergulhOS/application/views/conecte/template.php", "w", encoding="utf-8") as f:
        f.write(template)

    print("Success")
except Exception as e:
    print(e)
