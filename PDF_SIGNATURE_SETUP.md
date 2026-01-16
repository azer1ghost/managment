# PDF İmza Modulu - Quraşdırma Təlimatları

## Python Paketlərinin Quraşdırılması

PDF imza modulunun işləməsi üçün serverdə Python və aşağıdakı paketlər quraşdırılmalıdır:

### 1. Serverə SSH ilə bağlanın

```bash
ssh username@your-server-ip
```

### 2. Python3 və pip-in quraşdırılıb-quraşdırılmadığını yoxlayın

```bash
python3 --version
pip3 --version
```

### 3. Lazımi paketləri quraşdırın

```bash
pip3 install pymupdf pillow
```

Və ya əgər `pip3` yoxdursa:

```bash
pip install pymupdf pillow
```

### 4. Quraşdırmanı yoxlayın

```bash
python3 -c "import fitz; from PIL import Image; print('Bütün paketlər quraşdırılıb!')"
```

Əgər xəta yoxdursa, quraşdırma uğurlu olmuşdur.

## Alternativ: Virtual Environment istifadəsi

Əgər sistem səviyyəsində quraşdırmaq istəmirsinizsə, virtual environment istifadə edə bilərsiniz:

```bash
# Virtual environment yaradın
python3 -m venv /path/to/venv

# Aktivləşdirin
source /path/to/venv/bin/activate

# Paketləri quraşdırın
pip install pymupdf pillow
```

**Qeyd**: Virtual environment istifadə edəndə, Python scriptində tam yol göstərməlisiniz.

## Xəta halında

Əgər `pip` tapılmırsa, quraşdırın:

**Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install python3-pip
```

**CentOS/RHEL:**
```bash
sudo yum install python3-pip
```

**macOS:**
```bash
brew install python3
```

## Test

Quraşdırmadan sonra Laravel tətbiqində PDF imza səhifəsinə daxil olun və Python yoxlamasının uğurlu olduğunu görəcəksiniz.
